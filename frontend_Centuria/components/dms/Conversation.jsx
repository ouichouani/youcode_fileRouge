"use client"
import { useContext, useEffect, useRef, useState } from "react";
import { AppContext } from "@/context/AppContext.jsx";
import { DiscussionContext } from "@/context/DiscussionContext.jsx";
import getEcho from "@/lib/echo.ts";
import diffForHumans from "@/lib/diffForHumans";



export default function Conversation() {

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

    // CHECK IF USER IN CHAT NOW
    const [userInChat, setUserInChat] = useState(false);
    const [isTyping, setIsTyping] = useState(false);

    function scroll() {
        if (!messagesContainer.current) return;
        messagesContainer.current.scrollTo({
            top: messagesContainer.current.scrollHeight,
            behavior: 'smooth'
        });
    }

    function handleChange(e) {
        const { value } = e.target;
        setMessageInput(value);

        // HERE WE SEND THE WHISPER TO THE OTHER USER THAT WE ARE TYPING SOMTHING
        channel.current.whisper('typing', { name: user.name, id: user.id });
    }

    async function fetchConversationMessages() {
        const response = await fetch(`${domain}/conversations/messages/${currentDiscussion.id}`, {
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
        if (!messageInput.trim() || !currentDiscussion.user.id) return;

        const response = await fetch(`${domain}/conversations/messages/${currentDiscussion.id}`, {
            credentials: "include",
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            },
            body: JSON.stringify({ message: messageInput, receiver_id: currentDiscussion.user.id })
        });
        const data = await response.json();
        if (response.ok) setMessageInput('');
    }

    function chatInteractions() {
        const echo = getEcho();
        if (!echo) return;

        channel.current = echo.join(`conversations.${currentDiscussion.id}`);

        channel.current.subscribed(e => {
            console.log('SUBSCRIBED TO CONVERSATION ', currentDiscussion.id);
        })
            .here(users => {
                users.forEach(element => {
                    if (element.id == currentDiscussion.user.id) setUserInChat(true);
                });
            })
            .joining(users => {
                setUserInChat(true);
            })
            .leaving(users => {
                setUserInChat(false);
            })
            .listen(".conversation.message.sent", (e) => {
                setMessages(prev => [...prev, e.message]);
                scroll();
            })

            .listenForWhisper("typing", (e) => {
                // WE GET THE WHISPER , UPDATE THE STATE , START A TIMEOUT FOR 1S TO STOP TYPING
                if (e.id === currentDiscussion.user.id) {
                    setIsTyping(true)
                    clearTimeout(timeOut.current);
                    timeOut.current = setTimeout(() => {
                        setIsTyping(false);
                    }, 5000);
                }
            })
            .error((error) => {
                console.log("CONVERSATION CHANNEL ERROR:", error);
            });

        return () => {
            console.log('LEAVE CONV ; ' , currentDiscussion.id ) ;
            echo.leave(`conversations.${currentDiscussion.id}`);
        };

    }

    useEffect(() => {
        scroll();
    }, [messages]);

    useEffect(() => {
        if (!currentDiscussion?.id) return;
        fetchConversationMessages();
        const cleanup = chatInteractions();
        // THE CLEAN UP IN THE FUNCTION TO CLEAN THE EVENT
        return cleanup;
    }, [currentDiscussion.discussion?.id]);


    return (
        <>


            <div className="h-full flex flex-col  rounded-lg overflow-hidden">
                {currentDiscussion?.id &&
                    <>
                        <section className="bg-[#151b23] flex items-center gap-4 p-2">
                            <div className="relative">
                                <img className="w-13 h-13 rounded-full" src={currentDiscussion?.user.image?.url ?? "/images/blank-profile.webp"} />
                                {onlineUsers[currentDiscussion?.user.id] && <div className="w-3 h-3 bg-green-600 rounded-full absolute bottom-0 right-0 "></div>}
                                {isTyping && <div className="w-3 h-3 bg-orange-500 rounded-full absolute bottom-0 right-0 "></div>}
                            </div>

                            <div>
                                <span className="text-lg">{currentDiscussion?.user.name}</span>
                                <p className="text-[#9198a1] text-xs">
                                    {onlineUsers[currentDiscussion?.user.id]
                                        ? "Online"
                                        : `Last seen ${diffForHumans(currentDiscussion?.user.last_seen_at)}`}
                                </p>
                            </div>
                        </section>

                        <section ref={messagesContainer} className="flex-1 flex flex-col gap-2 p-12 overflow-auto bg-yel low-500">
                            {messages?.map((item, index) =>
                                <div key={item.id} className={`flex relative bg-re d-500 gap-2 max-w-[70%] ${item.sender_id == user.id ? "self-end" : "self-start"} `}>
                                    {messages[index - 1]?.sender_id != item.sender_id &&
                                        <img className={`w-8 h-8 rounded-full absolute ${item.sender_id == user.id ? "-right-10" : "-left-10"} `} src={item.sender_id != user.id ? (currentDiscussion?.user.image?.url ?? "/images/blank-profile.webp") : (user.image?.url ?? "/images/blank-profile.webp")} />
                                    }
                                    <div className={`w-full self-end rounded-lg ${item.sender_id == user.id ? "bg-[#212830] self-end" : "bg-green-800 self-start"} `}>
                                        <p className="py-2 px-6">{item.message}</p>
                                    </div>
                                </div>
                            )}
                        </section>

                        <form onSubmit={sendMessage} className="flex gap-1 items-center py-5 px-2">
                            <input type="text" name='content' placeholder="comment" required value={messageInput} onChange={handleChange}
                                className="w-full p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 " />
                            <button>
                                <svg className="cursor-pointer transition hover:text-blue-400" width="25px"
                                    height="25px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" strokeWidth="0" />
                                    <g id="SVGRepo_tracerCarrier" strokeLinecap="round" strokeLinejoin="round" />
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M10.3009 13.6949L20.102 3.89742M10.5795 14.1355L12.8019 18.5804C13.339 19.6545 13.6075 20.1916 13.9458 20.3356C14.2394 20.4606 14.575 20.4379 14.8492 20.2747C15.1651 20.0866 15.3591 19.5183 15.7472 18.3818L19.9463 6.08434C20.2845 5.09409 20.4535 4.59896 20.3378 4.27142C20.2371 3.98648 20.013 3.76234 19.7281 3.66167C19.4005 3.54595 18.9054 3.71502 17.9151 4.05315L5.61763 8.2523C4.48114 8.64037 3.91289 8.83441 3.72478 9.15032C3.56153 9.42447 3.53891 9.76007 3.66389 10.0536C3.80791 10.3919 4.34498 10.6605 5.41912 11.1975L9.86397 13.42C10.041 13.5085 10.1295 13.5527 10.2061 13.6118C10.2742 13.6643 10.3352 13.7253 10.3876 13.7933C10.4468 13.87 10.491 13.9585 10.5795 14.1355Z"
                                            stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                            strokeLinejoin="round" />
                                    </g>
                                </svg>
                            </button>
                        </form>
                    </>
                }
            </div>

        </>
    )
}