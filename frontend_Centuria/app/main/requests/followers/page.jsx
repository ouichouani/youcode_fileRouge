'use client';
import Link from "next/link";
import { useContext, useEffect, useState } from "react";
import { AppContext } from '@/context/AppContext.jsx';
import DeleteFriendRequestButton from '@/components/requests/DeleteFriendRequestButton.jsx'



export default function FollowersPage() {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const [followers, setFollowers] = useState([]);
    const { user } = useContext(AppContext);


    async function fetchFollowers(id) {
        if (!id) return;
        const response = await fetch(`${domain}/requests/${id}/followers`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
            },
        });
        const data = await response.json();
        setFollowers(data.followers);
        console.log(data);
    }

    useEffect(() => {
        fetchFollowers(user.id);
    }, [user?.id]);


    return (
        <section className="mx-auto w-full max-w-5xl pt-10">

            <div className="flex flex-col gap-2">
                {followers?.length ?
                    followers.map((f) =>
                        <article key={f.id} className={`rounded-2xl border border-white/10 bg-[#151b23] p-2  shadow-lg transition`}> 

                            <div className="flex justify-between items-center ">

                                <Link href={`/main/community/network/users/${f.sender?.id}`} className="flex items-center gap-4">
                                    <img src={f.sender?.image?.url || '/images/blank-profile.webp'}
                                        alt={f.sender?.name ?? 'undefind'}
                                        className={`h-13 w-13 rounded-full border border-white/20 bg-[#0d1117] object-cover`} />

                                    <div>
                                        {/* <Link href={`/main/requests/followers/${f.id}`}> */}
                                            <p className=" text-md font-semibold text-white">{f?.sender.name ?? 'undefined'}</p>
                                            <p className="mt-1 text-xs  text-[#9198a1]">{f?.sender.email ?? 'undefined'}</p>
                                        {/* </Link> */}
                                    </div>
                                        </Link>

                                <div className="flex flex-wrap gap-3" title='remove this follower'>
                                    <DeleteFriendRequestButton id={f.id} setFriendRequests={setFollowers}  />
                                </div>

                            </div>

                        </article>

                    ) :
                    <div className="rounded-2xl border border-dashed border-white/15 bg-[#151b23] p-8 text-center shadow-lg">
                        <p className="text-base text-[#9198a1]">there is no followers yet</p>
                    </div>
                }
            </div>

        </section>
    )

}