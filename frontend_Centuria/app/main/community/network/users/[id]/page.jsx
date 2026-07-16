"use client";
import { useParams } from "next/navigation"
import ShowUser from "@/components/users/ShowUser";

export default function showUserPage(){
    const {id} = useParams('id') ;

    return (
        <ShowUser user_id={id}/>
    )
} 