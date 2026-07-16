"use client";
import ListUsers from "@/components/users/ListUsers";
import { AppContext } from "@/context/AppContext.jsx";
import { useContext, useEffect, useState } from "react";




export default function NetworkPage() {
    const { user } = useContext(AppContext);
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const [users , setUsers] = useState([]) ;

    async function fetchUser() {
        const response = await fetch(`${domain}/users`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
        console.log(data)
        if (response.ok) setUsers(data.users);
    }

    useEffect(()=>{fetchUser()} , []) ;

 


    return (
        <section className="mx-auto w-full max-w-5xl pt-10">

            <div className="flex flex-col gap-2">
                {users?.map((item , key) => 
                <ListUsers key={item.id} user={item} />
                )}
            </div>

        </section>
    )
}