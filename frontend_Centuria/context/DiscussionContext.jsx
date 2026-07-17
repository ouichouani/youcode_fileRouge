"use client";
import { createContext, useEffect, useState } from "react";
import { usePathname } from "next/navigation";
import getEcho from "@/lib/echo";

export const DiscussionContext = createContext({});

export default function DiscussionProvider({ children }) {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const [currentDiscussion, setCurrentDiscussion] = useState({ type: null, id: null });
    const [discussions, setDiscussions] = useState([]);

    function sortDiscussion(discussions) {
        return [...discussions].sort((a, b) => {
            const dateA = a.last_message
                ? new Date(a.last_message.created_at).getTime()
                : 0;

            const dateB = b.last_message
                ? new Date(b.last_message.created_at).getTime()
                : 0;

            return dateB - dateA;
        });
    }

    async function fetchDiscussions() {
        const response = await fetch(`${domain}/discussions`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
            },
        });
        const data = await response.json();
        console.log("DISCUSSION FETCHED IN DISCUSSION CONTEXT : ", data);
        setDiscussions(sortDiscussion(data.discussions));
    }

    function addDiscussion(discussion) {
        // discussion SHOULD CONTAIN (id , user , type , avatar , title , lastMessage)

        setDiscussions(prev => {
            const exists = prev.some(
                item =>
                    item.id === discussion.id &&
                    item.type === discussion.type
            );

            if (exists) return prev;
            return sortDiscussion([...prev, discussion]);
        });
    }

    function refreshDiscussion(discussion) {
        // discussion SHOULD CONTAIN THE ( ID , TYPE ) AND THE NEW PROPERTIES (user , avatar , title , lastMessage)
        // THIS COMPONENT WILL REPLACE THE DISCUSSION AND REORDER THE CHATLIST

        setDiscussions(prev => {
            const UpdatedArray = prev.map(item => (item.type == discussion.type && item.id == discussion.id) ? discussion : item);
            return sortDiscussion(UpdatedArray)
        })
    }

    function deleteDiscussion(discussion) {

        // (discussion) SHOULD CONTAIN ( ID , TYPE) 

        // IF THE USER DELETE THE CURRENT DISCUSSION 
        if (currentDiscussion && discussion.id === currentDiscussion.id && discussion.type === currentDiscussion.type) {
            setCurrentDiscussion({ type: null, id: null });
        }

        // UPDATE THE DISCUSSIONS LIST
        setDiscussions(prev => prev.filter((item) => !(item.type === discussion.type && item.id === discussion.id)));
    }

    function openDiscussion(discussion) {
        // discussion SHOULD CONTAIN (id , user , type , avatar , title , lastMessage) ;
        setCurrentDiscussion(discussion);
    }

    useEffect(() => {
        fetchDiscussions();
        return () => {
            // THE COMPONENT MAY HAVE THE LAST VALUE IF USER LOGOUT SO I THINK THIS IS MORE SECURE
            setCurrentDiscussion({ type: null, id: null })
        }
    }, []);


    const sharedValues = {
        discussions: discussions,
        currentDiscussion: currentDiscussion,
        setCurrentDiscussion: setCurrentDiscussion,
        addDiscussion: addDiscussion,
        refreshDiscussion: refreshDiscussion,
        deleteDiscussion: deleteDiscussion,
        openDiscussion: openDiscussion,
    }

    return (
        <DiscussionContext.Provider value={{ ...sharedValues }} >
            {children}
        </DiscussionContext.Provider>
    )
}