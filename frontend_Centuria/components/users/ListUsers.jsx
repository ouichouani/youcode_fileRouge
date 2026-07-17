'use client';
import { useContext, useEffect, useState } from "react";
import { AppContext } from '@/context/AppContext.jsx'
import Link from 'next/link'



export default function ListUsers({ user, setBlackList }) {

    // this component show a list of users that we can (add friend , ban ) used in both black list and active users


    const { user: authUser, notify } = useContext(AppContext);
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const [targetUser, setTargetUser] = useState({ ...user });

    // PERMITIONS
    // FR = FRIEND REQUEST
    const ModeratorCanBan = (targetUser.is_banned_by_moderator && authUser.role == 'Moderator' && targetUser.role != 'Admin' && targetUser.id != authUser.id)
    const AdminCanBan = (authUser.role == 'Admin' && targetUser.role != 'Admin' && targetUser.id != authUser.id);
    const [canCancelFR, setCanCancelFR] = useState(targetUser.id != authUser.id && targetUser.is_friend == false && targetUser.received_requests?.length != 0);
    const [canRejectFR, setCanRejectFR] = useState(targetUser.id != authUser.id && targetUser.is_friend == false && targetUser.sent_requests?.length != 0);
    const [canCreateFR, setCanCreateFR] = useState(targetUser.id != authUser.id && targetUser.is_friend == false && targetUser.sent_requests?.length == 0 && targetUser.received_requests?.length == 0);
    const [canAcceptFR, setCanAcceptFR] = useState(targetUser.id != authUser.id && targetUser.is_friend == false && targetUser.sent_requests?.length != 0);

    function updatePermitions(action = 'AcceptFR') {

        setCanCreateFR(false);
        setCanCancelFR(false);
        setCanRejectFR(false);
        setCanAcceptFR(false);

        switch (action){
            case 'CancelFR' : return setCanCreateFR(true) ;
            case 'RejectFR' : return setCanCreateFR(true) ;
            case 'CreateFR' : return setCanCancelFR(true) ;
            default : return  ;
        }
    }

    async function ban() {
        const response = await fetch(`${domain}/users/${targetUser.id}/ban`, {
            method: "POST",
            credentials: "include", // store the cookie from the response
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json",
            },
        });

        const result = await response.json();
        console.log(result)
        if (response.ok) setBlackList(prev => prev.filter(item => item.id != targetUser.id))
    }

    async function addFriend() {
        const response = await fetch(`${domain}/requests`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            },
            body: JSON.stringify({ receiver_id: targetUser.id })
        });

        const data = await response.json();
        console.log(data)
        if (response.ok) {
            setTargetUser(prev => ({ ...prev, received_requests: [data.friendRequest] }))
            updatePermitions('CreateFR');
            notify("friend request sended with success");
        }
    }

    async function canselFriendRequest() {
        const response = await fetch(`${domain}/requests/${targetUser.received_requests[0].id}`, {
            method: 'DELETE',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        if (response.ok) {
            setTargetUser(prev => ({ ...prev, received_requests: [] }))
            updatePermitions('CancelFR') ;
            notify("friend request canceled with success", 'orange');
        }
        const data = await response.json();
    }

    async function rejectFriendRequest() {
        const response = await fetch(`${domain}/requests/${targetUser.sent_requests[0].id}/reject`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
        if (response.ok) {
            setTargetUser(prev => ({ ...prev, sent_requests: [] }))
            updatePermitions('RejectFR') ;
            notify("friend request rejected with success", "orange");
        }
    }

    async function acceptFriendRequest() {
        const response = await fetch(`${domain}/requests/${targetUser.sent_requests[0].id}/accept`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
        if (response.ok) {
            setTargetUser(prev => ({ ...prev, sent_requests: [] }))
            updatePermitions() ;
            notify("friend request accepted with success");
        }
    }



    return (

        <article key={targetUser.id} className="rounded-2xl border border-white/10 bg-[#151b23] p-2  shadow-lg">
            <div className="flex gap-5 justify-between items-center ">

                <Link href={`/main/community/network/users/${targetUser.id}`} className="flex items-start gap-4">
                    <img src={targetUser.image?.url || '/images/blank-profile.webp'}
                        alt={targetUser.name}
                        className="h-10 w-10  rounded-full border border-white/20 bg-[#0d1117] object-cover" />
                    <div className="flex-1">

                        <span href={`/main/community/network/users/${targetUser.id}`}
                            className={` text-md font-semibold transition hover:text-white ${targetUser.is_banned ? "text-red-500" : "text-white"}`}>
                            {targetUser.name}
                        </span>
                        <p className="mt-1 text-sm text-[#9198a1]">{targetUser.email}</p>
                    </div>
                </Link>

                <div className="flex flex-wrap gap-1">

                    {canCreateFR &&
                        <button onClick={addFriend} title="add friend" className="flex items-center justify-center rounded-full cursor-pointer border border-white/30 bg-green-500/10  p-2 text-sm font-medium text-red-200 transition hover:bg-green-500/20">
                            <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path fill="#ffff" d="M285.7 368C384.2 368 464 447.8 464 546.3C464 562.7 450.7 576 434.3 576L77.7 576C61.3 576 48 562.7 48 546.3C48 447.8 127.8 368 226.3 368L285.7 368zM528 144C541.3 144 552 154.7 552 168L552 216L600 216C613.3 216 624 226.7 624 240C624 253.3 613.3 264 600 264L552 264L552 312C552 325.3 541.3 336 528 336C514.7 336 504 325.3 504 312L504 264L456 264C442.7 264 432 253.3 432 240C432 226.7 442.7 216 456 216L504 216L504 168C504 154.7 514.7 144 528 144zM256 312C189.7 312 136 258.3 136 192C136 125.7 189.7 72 256 72C322.3 72 376 125.7 376 192C376 258.3 322.3 312 256 312z" />
                            </svg>
                        </button>
                    }

                    {canAcceptFR &&
                        <button onClick={acceptFriendRequest} title="add friend" className="flex items-center justify-center rounded-full cursor-pointer border border-white/30 bg-green-500/10  p-2 text-sm font-medium text-red-200 transition hover:bg-green-500/20">
                            <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path fill="#ffff" d="M286 368C384.5 368 464.3 447.8 464.3 546.3C464.3 562.7 451 576 434.6 576L78 576C61.6 576 48.3 562.7 48.3 546.3C48.3 447.8 128.1 368 226.6 368L286 368zM585.7 169.9C593.5 159.2 608.5 156.8 619.2 164.6C629.9 172.4 632.3 187.4 624.5 198.1L522.1 338.9C517.9 344.6 511.4 348.3 504.4 348.7C497.4 349.1 490.4 346.5 485.5 341.4L439.1 293.4C429.9 283.9 430.1 268.7 439.7 259.5C449.2 250.3 464.4 250.6 473.6 260.1L500.1 287.5L585.7 169.8zM256.3 312C190 312 136.3 258.3 136.3 192C136.3 125.7 190 72 256.3 72C322.6 72 376.3 125.7 376.3 192C376.3 258.3 322.6 312 256.3 312z" />
                            </svg>
                        </button>
                    }

                    {canRejectFR &&
                        <button onClick={rejectFriendRequest} title="reject friend request" className="rounded-full cursor-pointer border border-red-400/30 bg-red-500/10  p-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                            <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path fill="#ffff" d="M286.1 368C384.6 368 464.4 447.8 464.4 546.3C464.4 562.7 451.1 576 434.7 576L78.1 576C61.7 576 48.4 562.7 48.4 546.3C48.4 447.8 128.2 368 226.7 368L286.1 368zM562.3 172.1C571.7 162.7 586.9 162.7 596.2 172.1C605.5 181.5 605.6 196.7 596.2 206L562.3 239.9L596.2 273.8C605.6 283.2 605.6 298.4 596.2 307.7C586.8 317 571.6 317.1 562.3 307.7L528.4 273.8L494.5 307.7C485.1 317.1 469.9 317.1 460.6 307.7C451.3 298.3 451.2 283.1 460.6 273.8L494.5 239.9L460.6 206C451.2 196.6 451.2 181.4 460.6 172.1C470 162.8 485.2 162.7 494.5 172.1L528.4 206L562.3 172.1zM256.4 312C190.1 312 136.4 258.3 136.4 192C136.4 125.7 190.1 72 256.4 72C322.7 72 376.4 125.7 376.4 192C376.4 258.3 322.7 312 256.4 312z" />
                            </svg>
                        </button>
                    }
                    {canCancelFR &&
                        <button onClick={canselFriendRequest} title="cancel sended friend request" className="rounded-full cursor-pointer border border-orange-400/30 bg-orange-500/10  p-2 text-sm font-medium text-orange-200 transition hover:bg-red-500/20">
                            <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path fill="#ffff" d="M286.1 368C384.6 368 464.4 447.8 464.4 546.3C464.4 562.7 451.1 576 434.7 576L78.1 576C61.7 576 48.4 562.7 48.4 546.3C48.4 447.8 128.2 368 226.7 368L286.1 368zM562.3 172.1C571.7 162.7 586.9 162.7 596.2 172.1C605.5 181.5 605.6 196.7 596.2 206L562.3 239.9L596.2 273.8C605.6 283.2 605.6 298.4 596.2 307.7C586.8 317 571.6 317.1 562.3 307.7L528.4 273.8L494.5 307.7C485.1 317.1 469.9 317.1 460.6 307.7C451.3 298.3 451.2 283.1 460.6 273.8L494.5 239.9L460.6 206C451.2 196.6 451.2 181.4 460.6 172.1C470 162.8 485.2 162.7 494.5 172.1L528.4 206L562.3 172.1zM256.4 312C190.1 312 136.4 258.3 136.4 192C136.4 125.7 190.1 72 256.4 72C322.7 72 376.4 125.7 376.4 192C376.4 258.3 322.7 312 256.4 312z" />
                            </svg>
                        </button>
                    }

                    {(ModeratorCanBan || AdminCanBan) &&
                        <button className="rounded-full cursor-pointer border border-red-400/30 bg-red-500/10  p-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20"
                            onClick={ban}>
                            {(targetUser.is_banned || targetUser.is_banned_by_moderator) ?
                                "unban"
                                :
                                <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                    <path fill="#ffff" d="M73 39.1C63.6 29.7 48.4 29.7 39.1 39.1C29.8 48.5 29.7 63.7 39 73.1L567 601.1C576.4 610.5 591.6 610.5 600.9 601.1C610.2 591.7 610.3 576.5 600.9 567.2L343.5 309.7C398.5 298.8 440 250.2 440 192C440 125.7 386.3 72 320 72C261.8 72 213.2 113.5 202.3 168.5L73 39.1zM267.6 369.4C179.9 380.6 112 455.5 112 546.3C112 562.7 125.3 576 141.7 576L474.2 576L267.6 369.4z" />
                                </svg>
                            }
                        </button>
                    }
                </div>

            </div>
        </article>

    )
}