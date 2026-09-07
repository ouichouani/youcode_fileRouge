"use client";
import Link from "next/link";
import { useContext, useEffect, useState } from "react";
import { AppContext } from '@/context/AppContext.jsx'
import { redirect } from "next/navigation";
import { useRouter } from "next/navigation";

import Post from "@/components/posts/Post.jsx";


export default function hidderPosts() {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { pathname } = useContext(AppContext);
    const [posts, setPosts] = useState([]);
    const router = useRouter();

    async function fetchPosts() {
        try {

            const response = await fetch(`${domain}/posts?h=1`, {
                method: "GET",
                credentials: "include", // store the cookie from the response
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
            });

            const result = await response.json();
            console.log(result);


            if (response.ok) setPosts(result.posts);
        } catch (error) {
            console.error('error from console : ' + error);
        }
    }

    useEffect(() => {
        fetchPosts();
    }, []);


    return (
        <section className="mx-auto w-full max-w-6xl flex item-center justify-center pt-10">

            {/* {pathname.includes("main/controll-panel") &&
                <div className="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
                    <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 className="text-xl font-bold tracking-wide text-white">hidden posts</h2>
                            <p className="mt-2 text-sm text-[#9198a1]">
                                Review hidden posts.
                            </p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3 hidden md:block">
                            <p className="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Total posts</p>
                            <p className="mt-2 text-lg font-semibold text-white">{posts.length}</p>
                        </div>
                    </div>
                </div>
            } */}

            <div className="flex flex-col gap-6">
                {(posts && posts.length) ?
                    posts?.map((post) => <Post  key={post.id} post={post} creator={post.user} setPosts={setPosts} />)
                    :
                    <div className="rounded-2xl border border-dashed border-white/15 bg-[#151b23] p-8 text-center shadow-lg">
                        <p className="text-base text-[#9198a1]">there is no posts yet</p>
                    </div>
                }
            </div>
        </section>


    )
}