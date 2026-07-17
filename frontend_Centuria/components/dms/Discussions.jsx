"use client";
import { useContext, useEffect } from "react";
import { NavContext } from "@/context/NavContext.jsx";
import { DiscussionContext } from "@/context/DiscussionContext.jsx";
import Conversation from "@/components/dms/Conversation";
import Group from "@/components/dms/Group";


export default function Discussions() {

    const { currentDiscussion } = useContext(DiscussionContext);
    const { setNav } = useContext(NavContext);


    useEffect(() => {
        setNav([]);
    }, []);

    return (
        <div className="h-full border border-white/10 rounded-lg">
            {currentDiscussion?.type == 'conversation' ?
                <Conversation />
                : currentDiscussion?.type == 'group' ?
                    <Group />
                    : ''
            }
        </div>
    )


}