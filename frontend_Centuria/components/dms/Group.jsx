"use client"
import { useContext, useEffect, useMemo, useRef, useState } from "react";
import Link from 'next/link'
import { AppContext } from "@/context/AppContext.jsx";
import { DiscussionContext } from "@/context/DiscussionContext.jsx";
import getEcho from "@/lib/echo.ts";
import diffForHumans from "@/lib/diffForHumans";



export default function Group() {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { user, onlineUsers } = useContext(AppContext);
    const { currentDiscussion } = useContext(DiscussionContext);


    // STORE CHANNEL IN UESREF TO SEND WHISPERS IN THE HANDLECHANGE TO INDICATE TYPING
    const channel = useRef(null);
    const timeOut = useRef(null);

    // USER TO SCROLL TO THE BOTTOM IN SCROLL FUNCTION
    const messagesContainer = useRef(null);

    //MESSAGES ARRAY
    const [messages, setMessages] = useState([]);
    const [messageInput, setMessageInput] = useState('');
    const [showGroup, setShowGroup] = useState(false);

    // CHECK IF USER IN CHAT NOW
    const [userInChat, setUserInChat] = useState([]);

    // USERS WHO ARE TYPING FOR THE MOMENT
    const [isTyping, setIsTyping] = useState({});

    function scroll() {
        if (!messagesContainer.current) return;
        messagesContainer.current.scrollTo({
            top: messagesContainer.current.scrollHeight,
            behavior: 'smooth'
        });
    }

    function toggleShowGroup() {
        setShowGroup(prev => !prev);
    }

    function handleChange(e) {
        const { value } = e.target;
        setMessageInput(value);

        // HERE WE SEND THE WHISPER TO THE OTHER USER THAT WE ARE TYPING SOMTHING
        channel.current.whisper('typing', { name: user.name, id: user.id, image: user.image.url });
    }

    async function fetchGroupMessages() {
        const response = await fetch(`${domain}/groups/messages/${currentDiscussion.id}`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
            },
        });
        const data = await response.json();
        setMessages(data.messages);
    }

    async function sendMessage(e) {
        e.preventDefault();
        if (!messageInput.trim() || !currentDiscussion.id) return;

        const response = await fetch(`${domain}/groups/messages/${currentDiscussion.id}`, {
            credentials: "include",
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            },
            body: JSON.stringify({ message: messageInput, group_id: currentDiscussion.id })
        });
        const data = await response.json();
        console.log("send data : ", data)
        if (response.ok) setMessageInput('');
    }

    function chatInteractions() {

        const echo = getEcho();
        if (!echo) return;

        channel.current = echo.join(`groups.${currentDiscussion.id}`);

        channel.current.subscribed(e => {
            console.log('SUBSCRIBED TO GROUP ', currentDiscussion.id);
        })
            .here(users => {
                users.forEach(element => {
                    setUserInChat(prev => [...prev, element]);
                });
            })
            .joining(users => {
                // show users in the group
            })
            .leaving(users => {
                // remove the user from 
            })
            .listen(".group.message.sent", (e) => {
                console.log()
                setMessages(prev => [...prev, e.message]);
                scroll();
            })

            .listenForWhisper("typing", (e) => {
                // WE GET THE WHISPER , UPDATE THE STATE , START A TIMEOUT FOR 1S TO STOP TYPING

                setIsTyping(prev => ({ ...prev, [e.id]: { name: e.name, image: e.image } }));

                clearTimeout(timeOut.current);

                timeOut.current = setTimeout(() => {
                    const typingObject = { ...isTyping };
                    delete typingObject[e.id];
                    setIsTyping({ ...typingObject });
                }, 5000);

            })

            .error((error) => {
                console.log("GROUP CHANNEL ERROR:", error);
            });

        return () => {
            console.log(`LEAV currentDiscussion.${currentDiscussion.id}`)
            echo.leave(`groups.${currentDiscussion.id}`);
        };

    }

    useEffect(() => {
        scroll();
    }, [messages]);


    useEffect(() => {
        if (!currentDiscussion?.id) return;
        console.log("currentDiscussion : ", currentDiscussion);
        fetchGroupMessages();

        const cleanup = chatInteractions();
        // THE CLEAN UP IN THE FUNCTION TO CLEAN THE EVENT
        return cleanup;

    }, [currentDiscussion.id]);

    return (
        <>
            {showGroup ?
                <ShowGroup toggleShowGroup={toggleShowGroup} group_id={currentDiscussion?.id} />
                :
                <div className="h-full flex flex-col rounded-lg overflow-hidden">
                    {currentDiscussion?.id &&
                        <>
                            <section className="bg-[#151b23] flex items-center gap-4 p-2">
                                <div className="relative" onClick={toggleShowGroup}>
                                    <img
                                        className="w-13 h-13 rounded-full object-cover"
                                        src={currentDiscussion.avatar?.url ?? "/images/blank-currentDiscussion.webp"}
                                        alt={currentDiscussion.title}
                                    />
                                </div>

                                <div>
                                    <h1 className="text-lg">{currentDiscussion.title}</h1>
                                    <p className="text-[#9198a1] text-xs">
                                        {currentDiscussion.members_count} members
                                    </p>
                                </div>
                            </section>

                            <section
                                ref={messagesContainer}
                                className="flex-1 flex flex-col gap-2 p-12 overflow-auto"
                            >
                                {messages?.map((item, index) => (
                                    <div
                                        key={item.id}
                                        className={`flex relative gap-2 max-w-[70%] ${item.sender_id === user.id
                                            ? "self-end"
                                            : "self-start"
                                            }`}
                                    >
                                        {messages[index - 1]?.sender_id !== item.sender_id && (
                                            <img
                                                className={`w-8 h-8 rounded-full absolute ${item.sender_id === user.id
                                                    ? "-right-10"
                                                    : "-left-10"
                                                    }`}
                                                src={
                                                    item.sender_id === user.id
                                                        ? user.image?.url ??
                                                        "/images/blank-profile.webp"
                                                        : item.sender.image?.url ??
                                                        "/images/blank-profile.webp"
                                                }
                                                alt={item.sender.name}
                                            />
                                        )}

                                        <div
                                            className={`rounded-lg ${item.sender_id === user.id
                                                ? "bg-[#212830]"
                                                : "bg-green-800"
                                                }`}
                                        >
                                            {/* Show sender name only for other users */}
                                            {item.sender_id !== user.id &&
                                                messages[index - 1]?.sender_id !==
                                                item.sender_id && (
                                                    <p className="px-6 pt-2 text-xs font-semibold text-green-300">
                                                        {item.sender.name}
                                                    </p>
                                                )}

                                            <p className="px-6 py-2">{item.message}</p>
                                        </div>
                                    </div>
                                ))}
                            </section>

                            <form
                                onSubmit={sendMessage}
                                className="flex gap-1 items-center py-5 px-2"
                            >
                                <input
                                    type="text"
                                    value={messageInput}
                                    onChange={handleChange}
                                    placeholder="Write a message..."
                                    className="w-full p-1 px-2 bg-[#151b23] border border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2"
                                />

                                <button type="submit">
                                    <svg
                                        className="cursor-pointer transition hover:text-blue-400"
                                        width="25"
                                        height="25"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M10.3009 13.6949L20.102 3.89742M10.5795 14.1355L12.8019 18.5804C13.339 19.6545 13.6075 20.1916 13.9458 20.3356C14.2394 20.4606 14.575 20.4379 14.8492 20.2747C15.1651 20.0866 15.3591 19.5183 15.7472 18.3818L19.9463 6.08434C20.2845 5.09409 20.4535 4.59896 20.3378 4.27142C20.2371 3.98648 20.013 3.76234 19.7281 3.66167C19.4005 3.54595 18.9054 3.71502 17.9151 4.05315L5.61763 8.2523C4.48114 8.64037 3.91289 8.83441 3.72478 9.15032C3.56153 9.42447 3.53891 9.76007 3.66389 10.0536C3.80791 10.3919 4.34498 10.6605 5.41912 11.1975L9.86397 13.42C10.041 13.5085 10.1295 13.5527 10.2061 13.6118C10.2742 13.6643 10.3352 13.7253 10.3876 13.7933C10.4468 13.87 10.491 13.9585 10.5795 14.1355Z"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                        />
                                    </svg>
                                </button>
                            </form>
                        </>
                    }
                </div>
            }

        </>
    )
}

function ShowGroup({ group_id, toggleShowGroup }) {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { user, onlineUsers, setCurrentDiscussion } = useContext(AppContext);
    const { currentDiscussion, deleteDiscussion } = useContext(DiscussionContext);

    const [group, setGroup] = useState(null);
    const [members, setMembers] = useState([]);

    // MODELS STATES 
    const [addMembersModelIsOpen, setAddMembersModelIsOpen] = useState(false);
    const [updateGroupModelIsOpen, setUpdateGroupModelIsOpen] = useState(false);
    const [deleteGroupModelIsOpen, setDeleteGroupModelIsOpen] = useState(false);

    // ROLES SHOULD BE CHANGED LATER
    const canAddMembers = group?.owner_id == user.id;
    const canRemoveMembers = group?.owner_id == user.id;
    const CanUpdateGroup = group?.owner_id == user.id;
    const CanDeleteGroup = group?.owner_id == user.id;

    async function fetchGroup() {
        const response = await fetch(`${domain}/groups/${group_id}`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
            },
        });
        const data = await response.json();
        console.log(data.group)
        setGroup(data.group);
        setMembers(data.group.users);
    }

    async function kickMembers(member) {
        // FUNCTION KICK A MEMBER FROM THE GROUP 
        // USED INSIDE (ToggleGroupMembers) FUNCTION . 

        const response = await fetch(`${domain}/groups/${group_id}/removeMembers/${member.id}`, {
            credentials: "include",
            method: "DELETE",
            headers: {
                "Accept": "application/json",
            },
        });

        const data = await response.json();
        setMembers(prev => {
            const filtredArray = members.filter((item) => item.id != member.id);
            return filtredArray;
        });
    }

    async function addMembers(member) {
        // FUNCTION ADD A MEMBER TO THE GROUP 
        // USED INSIDE (ToggleGroupMembers) FUNCTION .

        const response = await fetch(`${domain}/groups/${group_id}/addMembers/${member.id}`, {
            credentials: "include",
            method: "POST",
            headers: {
                "Accept": "application/json",
            },
        });

        const data = await response.json();
        setMembers(prev => [...prev, member]);
    }

    function toggleGroupMembers(member) {
        // CHECK IF THE MEMBER ALREADY EXISTS AND ADD IT OR REMOVE IT BASED ON MEMBERS STATE
        const memberExist = members.find(item => item.id == member.id)
        memberExist ? kickMembers(member) : addMembers(member);
    }



    useEffect(() => {
        fetchGroup();
    }, [group_id]);

    // SORT MEMBER USERS BY NAME AND ONLINE USERS TO TOP
    const orderedMembers = useMemo(() => {
        if (!members) return [];

        const sorted = [...members].sort((a, b) => {
            const aOnline = !!onlineUsers[a.id];
            const bOnline = !!onlineUsers[b.id];

            // SORT ONLINE USERS FIRST AND IF THEY ARE BOTH ONLINE SORT BY NAME
            if (aOnline !== bOnline) {
                return Number(bOnline) - Number(aOnline);
            }

            // SORT BY NAME
            return a.name.localeCompare(b.name);
        });

        if (group?.owner) {
            sorted.unshift(group.owner);
        }

        return sorted;
    }, [members, onlineUsers, group]);

    return (

        <div className="h-full">

            {addMembersModelIsOpen ?
                <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-11 backdrop-blur-xs">
                    <div className="z-13">
                        <ListFriends members={members} setMembers={setMembers} callback={toggleGroupMembers} />
                    </div>
                    <div className='absolute top-0 left-0 w-full h-full bg-black/50 ' onClick={() => { setAddMembersModelIsOpen(false) }}></div>
                </div>
                : updateGroupModelIsOpen ?
                    <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-11 backdrop-blur-xs">
                        <div className="z-13">
                            <UpdateModel group={group} />
                        </div>
                        <div className='absolute top-0 left-0 w-full h-full bg-black/50 ' onClick={() => { setUpdateGroupModelIsOpen(false) }}></div>
                    </div>
                    :
                    deleteGroupModelIsOpen ?
                        <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-11 backdrop-blur-xs">
                            <div className="z-13">
                                <DeleteGroupModal group={group} setIsModelOpen={setDeleteGroupModelIsOpen} callback={deleteDiscussion} />
                            </div>
                            <div className='absolute top-0 left-0 w-full h-full bg-black/50 ' onClick={() => { setDeleteGroupModelIsOpen(false) }}></div>
                        </div>
                        :
                        ""}

            <div className="relative h-full  py-5 flex flex-col gap-10 items-center border border-white/10 rounded-lg overflow-auto">

                <button className="absolute top-10 left-10 cursor-pointer" onClick={toggleShowGroup}>
                    <svg xmlns="http://www.w3.org/2000/svg" className="w-8 h-8" fill="#eeee" viewBox="0 0 640 640">
                        <path d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z" /></svg>
                </button>

                {group ?
                    <>
                        <section className="bg-[# 151b23] flex flex-col items-center justify-center gap-4 p-2 ">
                            <div>
                                <img
                                    className="w-30 h-30 rounded-full object-cover bg-[#151b23]"
                                    src={group?.image?.url ?? "/images/blank-group.png"}
                                    alt={group?.name}
                                />
                            </div>

                            <div className="flex flex-col items-center">
                                <h1 className="text-[2rem]">{group?.name}</h1>
                                <p className="text-[#9198a1] text-xs">
                                    {group?.description || 'no description'}
                                </p>
                            </div>
                        </section>

                        <section className="bg-[#15 1b23] flex justify-center ">
                            <div className="flex gap-5">

                                {CanUpdateGroup &&
                                    <button title="update group" onClick={() => { setUpdateGroupModelIsOpen(true) }}>
                                        <svg fill="#848b93" className="w-7 h-7 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                            <path d="M468 64C487.2 64 505.6 71.6 519.1 85.2L554.8 120.9C568.4 134.4 576 152.8 576 172C576 191.2 568.4 209.6 554.8 223.1L509.9 268L372 130.1L416.9 85.2C430.4 71.6 448.8 64 468 64zM338.1 164L338.1 164L476 301.9L260.9 517C250.2 527.7 236.8 535.5 222.2 539.6L94.4 575.1C86.1 577.4 77.1 575.1 71 568.9C64.9 562.7 62.5 553.8 64.8 545.5L100.4 417.8C104.5 403.2 112.2 389.9 123 379.1L304.1 197.9L287 180.9C277.6 171.5 262.4 171.5 253.1 180.9L153 281C143.6 290.4 128.4 290.4 119.1 281C109.8 271.6 109.7 256.4 119.1 247.1L219.1 146.9C247.2 118.8 292.8 118.8 320.9 146.9L338.1 164z" />
                                        </svg>
                                    </button>
                                }

                                {CanDeleteGroup &&
                                    <button title="delete group" onClick={() => { setDeleteGroupModelIsOpen(true) }} >
                                        <svg fill="#848b93" className="w-7 h-7 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                            <path d="M232.7 69.9C237.1 56.8 249.3 48 263.1 48L377 48C390.8 48 403 56.8 407.4 69.9L416 96L512 96C529.7 96 544 110.3 544 128C544 145.7 529.7 160 512 160L128 160C110.3 160 96 145.7 96 128C96 110.3 110.3 96 128 96L224 96L232.7 69.9zM128 208L512 208L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 208zM216 272C202.7 272 192 282.7 192 296L192 488C192 501.3 202.7 512 216 512C229.3 512 240 501.3 240 488L240 296C240 282.7 229.3 272 216 272zM320 272C306.7 272 296 282.7 296 296L296 488C296 501.3 306.7 512 320 512C333.3 512 344 501.3 344 488L344 296C344 282.7 333.3 272 320 272zM424 272C410.7 272 400 282.7 400 296L400 488C400 501.3 410.7 512 424 512C437.3 512 448 501.3 448 488L448 296C448 282.7 437.3 272 424 272z" />
                                        </svg>
                                    </button>
                                }

                                {canAddMembers &&
                                    <button title="add members" onClick={() => { setAddMembersModelIsOpen(true) }}>
                                        <svg fill="#848b93" className="w-7 h-7 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                            <path d="M285.7 368C384.2 368 464 447.8 464 546.3C464 562.7 450.7 576 434.3 576L77.7 576C61.3 576 48 562.7 48 546.3C48 447.8 127.8 368 226.3 368L285.7 368zM528 144C541.3 144 552 154.7 552 168L552 216L600 216C613.3 216 624 226.7 624 240C624 253.3 613.3 264 600 264L552 264L552 312C552 325.3 541.3 336 528 336C514.7 336 504 325.3 504 312L504 264L456 264C442.7 264 432 253.3 432 240C432 226.7 442.7 216 456 216L504 216L504 168C504 154.7 514.7 144 528 144zM256 312C189.7 312 136 258.3 136 192C136 125.7 189.7 72 256 72C322.3 72 376 125.7 376 192C376 258.3 322.3 312 256 312z" />
                                        </svg>
                                    </button>
                                }

                            </div>
                        </section>

                        <section className="px-5 bg-gree n-500 flex flex-col gap-2  max-w-[50vw] min-w-[30vw]" >
                            {orderedMembers?.map((item) =>

                                <article key={item.id} className="rounded-2xl border border-white/10 bg-[#151b23] p-2  shadow-lg">
                                    <div className="flex gap-5 justify-between items-center ">

                                        <Link href={`/main/community/network/users/${item.id}`} className="flex items-start gap-4">
                                            <div className="relative shrink-0">
                                                {onlineUsers[item.id] &&
                                                    <div className="w-3 h-3 bg-green-600 rounded-full absolute bottom-0 right-0 "></div>
                                                }
                                                <img src={item.image?.url || '/images/blank-profile.webp'}
                                                    alt={item.name}
                                                    className="h-10 w-10  rounded-full border border-white/20 bg-[#0d1117] object-cover" />
                                            </div>

                                            <div className="flex-1">
                                                <span href={`/main/community/network/users/${item.id}`}
                                                    className={` text-md font-semibold transition hover:text-white`}>
                                                    {item.name}
                                                </span>
                                                <p className="mt-1 text-sm text-[#9198a1]">{item.email}</p>
                                            </div>
                                        </Link>

                                        <div className="flex flex-wrap gap-1">

                                            {item.id == group.owner_id ?
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" className="w-10 h-7 fill-yellow-500">
                                                    <path d="M345 151.2C354.2 143.9 360 132.6 360 120C360 97.9 342.1 80 320 80C297.9 80 280 97.9 280 120C280 132.6 285.9 143.9 295 151.2L226.6 258.8C216.6 274.5 195.3 278.4 180.4 267.2L120.9 222.7C125.4 216.3 128 208.4 128 200C128 177.9 110.1 160 88 160C65.9 160 48 177.9 48 200C48 221.8 65.5 239.6 87.2 240L119.8 457.5C124.5 488.8 151.4 512 183.1 512L456.9 512C488.6 512 515.5 488.8 520.2 457.5L552.8 240C574.5 239.6 592 221.8 592 200C592 177.9 574.1 160 552 160C529.9 160 512 177.9 512 200C512 208.4 514.6 216.3 519.1 222.7L459.7 267.3C444.8 278.5 423.5 274.6 413.5 258.9L345 151.2z" />
                                                </svg>
                                                : (canRemoveMembers && item.id != user.id) &&
                                                <button title="kick out" onClick={() => { kickMembers(item) }} className="rounded-full cursor-pointer border border-orange-400/30 bg-orange-500/10  p-2 text-sm font-medium text-orange-200 transition hover:bg-red-500/20">
                                                    <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                                        <path fill="#ffff" d="M286.1 368C384.6 368 464.4 447.8 464.4 546.3C464.4 562.7 451.1 576 434.7 576L78.1 576C61.7 576 48.4 562.7 48.4 546.3C48.4 447.8 128.2 368 226.7 368L286.1 368zM562.3 172.1C571.7 162.7 586.9 162.7 596.2 172.1C605.5 181.5 605.6 196.7 596.2 206L562.3 239.9L596.2 273.8C605.6 283.2 605.6 298.4 596.2 307.7C586.8 317 571.6 317.1 562.3 307.7L528.4 273.8L494.5 307.7C485.1 317.1 469.9 317.1 460.6 307.7C451.3 298.3 451.2 283.1 460.6 273.8L494.5 239.9L460.6 206C451.2 196.6 451.2 181.4 460.6 172.1C470 162.8 485.2 162.7 494.5 172.1L528.4 206L562.3 172.1zM256.4 312C190.1 312 136.4 258.3 136.4 192C136.4 125.7 190.1 72 256.4 72C322.7 72 376.4 125.7 376.4 192C376.4 258.3 322.7 312 256.4 312z" />
                                                    </svg>
                                                </button>
                                            }

                                        </div>

                                    </div>
                                </article>
                            )}
                        </section>
                    </>
                    :
                    <h2>loading</h2>
                }
            </div>

        </div>
    )
}

function ListFriends({ callback, members }) {

    // callback IS A FUNCTION THAT DO A PROCEDURE BASED ON THE GIVING INPUT
    // THIS COMPONENT FETCH LIST OF AUTH USER FRIENDS 

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { onlineUsers } = useContext(AppContext);
    const [friendsList, setFriendsList] = useState([]);

    async function fetchFriends() {
        const response = await fetch(`${domain}/requests/all-friends`, {
            method: "GET",
            credentials: "include", // store the cookie from the response
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json"
            }
        });

        const data = await response.json();
        console.log(data)
        if (response.ok) {
            setFriendsList(data.friends);
        }
    }

    useEffect(() => {
        fetchFriends();
    }, [])


    return (
        <section className="mx-auto w-full p-2 rounded-lg border border-white/10 h-fit max-h-[60vh] overflow-auto">

            <div className='flex flex-col gap-1 justify-between items-center overflow-scroll max-h-[40vh]'>
                {friendsList?.length ?
                    friendsList?.map((item) => (
                        <div key={item.id} className="cursor-pointer transition duration-[300ms] flex items-center px-2 py-1 rounded-lg gap-4 bg-[#151b23] hover:bg-[#212830] md:w-[50vw] max-w-[600px] ">
                            <div className="relative">
                                {onlineUsers[item.id] && <div className="w-3 h-3 bg-green-600 rounded-full absolute bottom-0 right-0 "></div>}
                                <img src={item.image?.url || '/images/blank-profile.webp'}
                                    alt={item.name}
                                    className="h-13 w-13  rounded-full border border-white/20 bg-[#0d1117] object-cover" />
                            </div>
                            <div className="flex-1">
                                <span className={` text-md font-semibold transition hover:text-white`}>
                                    {item.name}
                                </span>
                                <p className="mt-1 text-sm text-[#9198a1]">{item.email}</p>
                            </div>

                            {members[item.id] ?
                                <button type='button' title='remove member' onClick={() => callback(item)}
                                    className="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                                    <span>-</span>
                                </button>
                                :
                                <button type='button' title='add member' onClick={() => callback(item)}
                                    className="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                                    <span>+</span>
                                </button>
                            }

                        </div>
                    ))
                    :
                    <h1 className="text-xl">you have no friends 😂</h1>
                }
            </div>
        </section>
    )
}

function UpdateModel({ group }) {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { user, onlineUsers } = useContext(AppContext);

    const [data, setData] = useState({ name: group.name ?? '', description: group.description ?? '', image: group.image });
    const [errors, setErrors] = useState({});


    function handleChange(e) {
        const { name, value } = e.target;
        if (name == 'image') {
            const file = e.target.files[0];
            const imageUrl = URL.createObjectURL(file);
            setData(prev => ({ ...prev, image: { file: file, url: imageUrl } }));
        } else {
            setData(prev => ({ ...prev, [name]: value }));
        }
    }

    async function handleSubmit(e) {

        e.preventDefault();
        const formData = new FormData();

        for (let item in data) {
            if (item == "image") {
                if (data[item].file instanceof File) formData.append('image', data[item].file);
            } else {
                formData.append(item, data[item]);
            }
        }

        try {
            const response = await fetch(`${domain}/groups/${group.id}`, {
                method: "PUT",
                credentials: "include",
                headers: {
                    "Accept": "application/json",
                },
                body: formData
            });

            const result = await response.json();
            console.log('OUTPUT OF GROUP UPDATE : ', result)

        } catch (error) {
            console.error('error from Group component : ' + error);
        }
    }


    return (
        <>
            <section className="min-w-[40vw]">
                <form onSubmit={handleSubmit} className="relative rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg w-full">

                    <div className='flex gap-3 items-center mb-10'>
                        <label htmlFor="image" className='cursor-pointer'>
                            <img src={data?.image?.url || "/images/blank-currentDiscussion.png"} alt="group image" className='w-15 h-15 rounded-full bg-[#9198a1]  object-cover' />
                            <input type='file' id='image' name="image" className='hidden' accept='image/*' onChange={handleChange} />
                        </label>
                        <div className='flex flex-col'>
                            <p className='text-lg'>{data.name || 'name of group'}</p>
                            <p className='text-[#9198a1] text-sm'>{data.description || 'group description'}</p>
                        </div>
                    </div>

                    <div className="name">
                        <label htmlFor="name" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">name</span>
                            <input type='text' name="name" id="name" onChange={handleChange} value={data?.name}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2" />
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.content && errors?.content[0]}</div>
                    </div>

                    <div className="description">
                        <label htmlFor="description" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">description</span>
                            <textarea name="description" id="description" rows="8" onChange={handleChange} value={data?.description}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2"></textarea>
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.content && errors?.content[0]}</div>
                    </div>

                    <div className="bg-[#151b23] absolute top-0 right-0 flex flex-col translate-x-[calc(100%+10px)] rounded-lg" >

                        <button type="button" className='cursor-pointer flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830]'>
                            <div className="cursor-pointer w-[25px] h-[25px]" >
                                <img src="/svg/out.svg" alt="cancel" className='w-full h-full hrink-0' />
                            </div>
                        </button>

                        <div className='cursor-pointer flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830]'>
                            <button type="submit" className="cursor-pointer w-[25px] h-[25px]" >
                                <img src="/svg/done.svg" alt="submit" className='w-full h-full hrink-0' />
                            </button>
                        </div>

                    </div>
                </form>
            </section>
        </>
    )
}

function DeleteGroupModal({ group, setIsModelOpen, callback }) {

    // callback IS THE EFFECT THAT WILL HAPPEND AFTER WE DELETE THE 
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { currentDiscussion } = useContext(DiscussionContext);

    async function deleteGroup() {

        try {

            const response = await fetch(`${domain}/groups/${group.id}`, {
                method: "DELETE",
                credentials: "include",
                headers: {
                    "Accept": "application/json",
                },
            });

            const data = await response.json();
            if (!response.ok) {
                console.error(data);
                return;
            }

            if (callback) {
                callback(currentDiscussion);
            }

            setIsModelOpen(false);

        } catch (error) {
            console.error(error);
        }

    }

    return (
        <div className="bg-[#151b23] p-5 border border-white/10 rounded-lg">

            <h2 className="text-xl font-semibold text-white">
                Delete Group
            </h2>

            <p className="mt-4 text-sm text-[#9198a1]">
                Are you sure you want to permanently delete
                <span className="font-semibold text-white">
                    {" "} {group?.name} {" "}
                </span>
                ?
            </p>

            <p className="mt-2 text-sm text-red-400">
                This action cannot be undone.
            </p>

            <div className="mt-8 flex justify-end gap-3">

                <button
                    onClick={() => setIsModelOpen(false)}
                    className="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer">

                    Cancel
                </button>

                <button
                    onClick={deleteGroup}
                    className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                    Delete Group
                </button>

            </div>
        </div>

    );
}