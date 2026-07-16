'use client';
import { AppContext } from '@/context/AppContext.jsx'
import { useCallback, useContext, useEffect, useRef, useState } from "react";
import { useRouter } from 'next/navigation';

export default function CreateGroupModel({ setIsModelOpen }) {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { user, notify, onlineUsers } = useContext(AppContext);

    const [members, setMembers] = useState({});

    // {id : {name , image}}

    const [errors, setErrors] = useState({});
    const [data, setData] = useState([]);
    const [isAddFriendsModelOpen, setIsAddFriendsModelOpen] = useState(false);
    const [friendsList, setFriendsList] = useState([]);

    async function fetchFriends(e) {
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

    async function contact(user) {
        const response = await fetch(`${domain}/conversations`, {
            credentials: "include",
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            },
            body: JSON.stringify({ user2_id: user.id })
        });

        const data = await response.json();
        console.log(data);
        setCurrentConversation({ id: data.conversation.id, type: "conversation", user: data.conversation.user });
        setIsModelOpen(false);
    }

    function toggleAddFriendsModel() {
        setIsAddFriendsModelOpen(prev => !prev);
    }

    function handleClick() {
        setIsModelOpen(prev => !prev);
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

        for (let id in members) {
            formData.append('members[]', id);
        }
        try {

            const response = await fetch(`${domain}/groups`, {
                method: "POST",
                credentials: "include", // store the cookie from the response
                headers: {
                    "Accept": "application/json",
                },
                body: formData
            });

            const result = await response.json();

            console.log(result)
            // if (response.ok) router.push('');
            // if (!response.ok) setErrors({ ...result.errors });


        } catch (error) {
            console.error('error from CreateGoupModel : ' + error);
        }
    }

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

    useEffect(() => {
        fetchFriends();
    }, []);

    return (
        <>
            <section className="min-w-[40vw]">
                <form onSubmit={handleSubmit} className="relative rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg w-full">

                    <div className='flex gap-3 items-center mb-10'>
                        <label htmlFor="image" className='cursor-pointer'>
                            <img src={data?.image?.url || "/images/blank-group.png"} alt="group image" className='w-15 h-15 rounded-full bg-[#9198a1]  object-cover' />
                            <input type='file' id='image' name="image" className='hidden' accept='image/*' onChange={handleChange} />
                        </label>
                        <div className='flex flex-col'>
                            <p className='text-lg'>{data.name || 'name of group'}</p>
                            <p className='text-[#9198a1] text-sm'>{data.bio || 'group bio'}</p>
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

                    <div className="bg-[#151b23] p-2 absolute top-0 left-0 flex flex-col gap-2 -translate-x-[calc(100%+10px)] rounded-lg" >

                        <button type="button" className='cursor-pointer flex items-center justify-center'>
                            <div className="cursor-pointer" >
                                <img src={user?.image?.url || '/images/blank-profile.webp'} alt="me" title='me' className='w-10 h-10 object-cover rounded-full border border-white/20' />
                            </div>
                        </button>

                        {Object.keys(members)?.map((id) => (
                            <button key={id} type="button" className='cursor-pointer flex items-center justify-center'>
                                <div className="cursor-pointer w-[2 5px] h-[2 5px]" >
                                    <img src={members[id]?.image?.url || '/images/blank-profile.webp'} alt="" className='w-10 h-10 object-cover rounded-full' />
                                </div>
                            </button>
                        ))}

                        <button type='button' title='add member' onClick={toggleAddFriendsModel}
                            className="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                            <span>+</span>
                        </button>


                    </div>
                </form>

            </section>

            {isAddFriendsModelOpen ?
                <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-11 backdrop-blur-xs">
                    <div className="z-13">
                        <FriendsList friendsList={friendsList ?? []} onlineUsers={onlineUsers} members={members} setMembers={setMembers} />
                    </div>
                    <div className='absolute top-0 left-0 w-full h-full bg-black/50 ' onClick={toggleAddFriendsModel}></div>
                </div>
                : ''}
        </>
    )
}


function FriendsList({ friendsList, onlineUsers, members, setMembers }) {

    // members ARE THE LIST OF THE ADDED MEMBERS ALREADY , SO THEY WILL APEAS WITH (-) NOT WITH (+)

    function toggleMember(item) {
        if (members[item.id] == null) {
            setMembers(prev => ({ ...prev, [item.id]: item }));
        }
    }


    return (
        <section className="mx-auto w-full p-2 rounded-lg border border-white/10 h-fit max-h-[60vh] overflow-auto">

            <div className='flex flex-col gap-1 justify-between items-center overflow-scroll max-h-[40vh]'>
                {friendsList?.map((item) => (
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
                            <button type='button' title='remove member' onClick={() => toggleMember(item)}
                                className="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                                <span>-</span>
                            </button>
                            :
                            <button type='button' title='add member' onClick={() => toggleMember(item)}
                                className="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                                <span>+</span>
                            </button>
                        }

                    </div>
                ))
                }
            </div>
        </section>
    )
}