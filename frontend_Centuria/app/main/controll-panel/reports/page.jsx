'use client'
import { useContext, useEffect, useState } from "react"
import { AppContext } from '@/context/AppContext.jsx'
import Post from '@/components/posts/Post.jsx';
import ReportContainer from '@/components/reports/ReportContainer.jsx';



export default function ReportsPage() {

    const { user } = useContext(AppContext);
    const [posts, setPosts] = useState([]);
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;

    async function fetchReports() {

        const response = await fetch(`${domain}/reports`, {
            method: "GET",
            credentials: "include", // store the cookie from the response
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json",
            },
        });

        const result = await response.json();
        console.log(result);
        setPosts(result.posts);

    }

    console.log(posts?.reports?.length)

    useEffect(() => {
        fetchReports();

    }, []);

    return (
        <section className="mx-auto w-full max-w-6xl mt-2">
            <div className="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
                <div className="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                    <div>
                        <h2 className="text-xl font-bold tracking-wide text-white">
                            {user?.role == 'Admin' ? 'confermed reports' : 'unconfermed reports'}</h2>
                    </div>

                </div>
            </div>

            <section className="mx-auto w-full flex justify-center max-w-6xl">

                <div className="flex flex-col gap-6">
                    {posts?.length ?
                        posts.map(post => <Post key={post.id} post={post} setPosts={setPosts} creator={post.user} Container={ReportContainer} type='reports' />)
                        :
                        <div className="rounded-2xl border border-dashed border-white/15 bg-[#151b23] p-8 text-center shadow-lg">
                            <p className="text-base text-[#9198a1]">there is no reported posts yet</p>
                        </div>
                    }
                </div>

            </section>
        </section>

    )
}