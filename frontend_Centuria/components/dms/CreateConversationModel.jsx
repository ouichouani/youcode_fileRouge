'use client';
import { AppContext } from '@/context/AppContext.jsx'
import { useCallback, useContext, useEffect, useRef, useState } from "react";
import { useRouter } from 'next/navigation';

export default function CreateConversationModel({ setIsModelOpen }) {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { notify, setCurrentConversation, onlineUsers } = useContext(AppContext);
    const [errors, setErrors] = useState({});
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

    function handleClick() {
        setIsModelOpen(prev => !prev);
    }

    useEffect(() => {
        fetchFriends();
    }, []);

    return (
        <section className="mx-auto w-full p-2 rounded-lg border border-white/10 h-fit max-h-[60vh] overflow-auto">
            <div className="bg-[#151b23] sticky top-0 z-2 rounded-lg mb-2 flex items-center justify-between gap-4 px-4 h-17 text-lg">
                <h1>start conversation</h1>
            </div>

            <div className='flex flex-col gap-1 justify-between items-center overflow-scroll max-h-[40vh]'>
                {friendsList.map((item) => (
                    <div key={item.id} onClick={() => contact(item)} className="cursor-pointer transition duration-[300ms] flex items-center px-2 py-1 rounded-lg gap-4 bg-[#151b23] hover:bg-[#212830] md:w-[50vw] max-w-[600px] ">
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
                    </div>
                ))
                }
            </div>
        </section>
    )
}