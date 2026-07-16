'use client';
import { useContext, useEffect, useMemo, useState } from "react";
import { AppContext } from "@/context/AppContext.jsx";
import { DiscussionContext } from "@/context/DiscussionContext.jsx";

import CreateConversationModel from "@/components/dms/CreateConversationModel";
import CreateGroupModel from "@/components/dms/CreateGroupModel";
import Link from "next/link";
import getEcho from "@/lib/echo"


export default function ChatList() {


    const { user, onlineUsers } = useContext(AppContext);
    const { discussions , openDiscussion , orderDiscussions } = useContext(DiscussionContext);

    const [conversations, setConversations] = useState([]);
    const [groups, setGroups] = useState([]);


    const [isConversationModelOpen, setIsConversationModelOpen] = useState(false);
    const [isGroupModelOpen, setIsGroupModelOpen] = useState(false);

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;


    function interactions() {

        const echo = getEcho();
        if (!user.id) return;

        // PERSONAL CHANNEL WHERE USER CAN RESEAVE ANY NOTIFICATION
        const PERSONAL_CHANNEL = echo.private(`users.${user.id}`);

        PERSONAL_CHANNEL.subscribed(() => {
            console.log("SUBSCRIBE TO PERSONAL CHANNEL . ", user.id);

        }).listen(".conversation.message.sent", (e) => {
            
            orderDiscussions() ;

        }).error((error) => {
            console.log("PRIVATE CHANNEL ERROR:", error);
        });

        return () => {
            echo.leave(`users.${user.id}`);
        };

    }

    function toggleConversationModel() {
        setIsConversationModelOpen(prev => !prev);
    }

    function toggleGroupModel() {
        setIsGroupModelOpen(prev => !prev);
    }


    useEffect(() => {

        interactions();

    }, [user]);




    return (
        <>
            <section className="bg-[#151b23] flex shrink-0 sticky top-0 z-2 rounded-lg items-center justify-between gap-4 px-4 h-17 text-lg">
                <h1>Discussions</h1>

                <div className="flex gap-4 items-center" >
                    <button onClick={toggleGroupModel}>
                        <svg xmlns="http://www.w3.org/2000/svg" width={"30px"} fill='#848b93' viewBox="0 0 640 512">
                            <path d="M192 256c61.9 0 112-50.1 112-112S253.9 32 192 32 80 82.1 80 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C51.6 288 0 339.6 0 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zM480 256c53 0 96-43 96-96s-43-96-96-96-96 43-96 96 43 96 96 96zm48 32h-3.8c-13.9 4.8-28.6 8-44.2 8s-30.3-3.2-44.2-8H432c-20.4 0-39.2 5.9-55.7 15.4 24.4 26.3 39.7 61.2 39.7 99.8v38.4c0 2.2-.5 4.3-.6 6.4H592c26.5 0 48-21.5 48-48 0-61.9-50.1-112-112-112z" />
                        </svg>
                    </button>

                    <button onClick={toggleConversationModel} className="cursor-pointer" >
                        <svg viewBox="-2.4 -2.4 28.80 28.80" width="35px" fill="#848b93">
                            <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
                            <g id="SVGRepo_tracerCarrier" strokeLinecap="round" strokeLinejoin="round" stroke="#CCCCCC" strokeWidth="4.704">
                                <line className="cls-1" fill="none" stroke="#848b93" strokeWidth="1.91px" x1="8.18" y1="10.07" x2="15.82" y2="10.07"></line><line className="cls-1" fill="none" stroke="#848b93" strokeWidth="1.91px" x1="12" y1="6.25" x2="12" y2="13.89"></line>
                                <path className="cls-1" fill="none" stroke="#848b93" strokeWidth="1.91px" d="M1.5,5.3v9.54a3.82,3.82,0,0,0,3.82,3.82H7.23v2.86L13,18.66h5.73a3.82,3.82,0,0,0,3.82-3.82V5.3a3.82,3.82,0,0,0-3.82-3.82H5.32A3.82,3.82,0,0,0,1.5,5.3Z"></path>
                            </g>
                            <g id="SVGRepo_iconCarrier">
                                <line className="cls-1" fill="none" stroke="#848b93" strokeWidth="1.91px" x1="8.18" y1="10.07" x2="15.82" y2="10.07"></line><line className="cls-1" fill="none" stroke="#848b93" strokeWidth="1.91px" x1="12" y1="6.25" x2="12" y2="13.89"></line>
                                <path className="cls-1" fill="none" stroke="#848b93" strokeWidth="1.91px" d="M1.5,5.3v9.54a3.82,3.82,0,0,0,3.82,3.82H7.23v2.86L13,18.66h5.73a3.82,3.82,0,0,0,3.82-3.82V5.3a3.82,3.82,0,0,0-3.82-3.82H5.32A3.82,3.82,0,0,0,1.5,5.3Z"></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </section>

            <div className="px-2 flex flex-col gap-1">
                {discussions.map((item, key) =>
                    <div onClick={() => openDiscussion(item)} key={key} className="cursor-pointer transition-all duration-300 bg-[#151b23] hover:bg-[#212830] rounded-lg flex items-center gap-4 p-2">
                        <div className="relative shrink-0">

                            {onlineUsers[item?.user?.id] &&
                                <div className="w-3 h-3 bg-green-600 rounded-full absolute bottom-0 right-0 "></div>
                            }

                            <img
                                src={item?.avatar?.url ?? "/images/blank-profile.webp"}
                                alt="contact profie"
                                className="object-cover w-12 h-12 rounded-full"
                            />
                        </div>

                        <div className="flex-1 min-w-0">
                            <h1 className="max-w-[80%] truncate">{item.title}</h1>
                            <p className="text-xs text-[#9198a1] max-w-[70%] truncate">{item.last_message?.sender_id == user.id ? 'you : ' : ''}{item.last_message?.message}</p>
                        </div>
                    </div>
                )}
            </div>

            {isConversationModelOpen ?
                <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-11 backdrop-blur-xs">
                    <div className="z-13">
                        <CreateConversationModel setIsModelOpen={setIsConversationModelOpen} />
                    </div>
                    <div className='absolute top-0 left-0 w-full h-full bg-black/50 ' onClick={toggleConversationModel}></div>
                </div>
                : ''}

            {isGroupModelOpen ?
                <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-11 backdrop-blur-xs">
                    <div className="z-13">
                        <CreateGroupModel setIsModelOpen={setIsGroupModelOpen} />
                    </div>
                    <div className='absolute top-0 left-0 w-full h-full bg-black/50 ' onClick={toggleGroupModel}></div>
                </div>
                : ''}
        </>
    )
}