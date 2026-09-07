"use client";
import { useRouter } from  "next/navigation";
import { AppContext } from '@/context/AppContext.jsx'
import { useContext } from "react";


export default function DeleteTask({ id }) {

    const { notify } = useContext(AppContext);
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const router = useRouter()

    async function handleClick() {
        const response = await fetch(`${domain}/tasks/${id}`, {
            method: "DELETE",
            credentials: "include",
            headers: {
                "accept": "Application/json",
                "Content-Type": "application/json",
            }
        });

            const data = await response.json();
            console.log(data) ;
            if(response.ok) {
                notify('task is deleted with success' , 'orange') ;
                router.push("/main/dashboard/tasks");
            }

    }

    return (
        <button onClick={handleClick}
            className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
            delete
        </button>
    )
}