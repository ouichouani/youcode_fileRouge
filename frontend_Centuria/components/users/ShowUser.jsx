"use client";

import Link from "next/link";
import { AppContext } from '@/context/AppContext.jsx'
import { useContext, useEffect, useState } from 'react';
import Post from "@/components/posts/Post.jsx";
import { useRouter } from "next/navigation";

import CommentContainer from '@/components/comments/CommentContainer.jsx';
import EditProfileModel from '@/components/users/EditProfileModel.jsx';


export default function ShowUser({ user_id }) {

    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const { user: authUser } = useContext(AppContext);
    const router = useRouter();
    const [user, setUser] = useState({});
    const [isFriend, setIsFriend] = useState(true);
    const [sentRequests, setSentRequests] = useState([]);
    const [receivedRequests, setReceivedRequests] = useState([]);
    const [pendingRequest, setPendingRequest] = useState();
    const [posts, setPosts] = useState([]);
    const [isModelOpen, setIsModelOpen] = useState(false);

    function toggleModel() {
        setIsModelOpen(prev => !prev);
    }

    async function fetchUser(id) {
        const response = await fetch(`${domain}/users/${id}`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
        setUser(data?.user)
        setSentRequests(data.sentRequests ?? [])
        setReceivedRequests(data.receivedRequests ?? [])
        setPosts(data.posts ?? []);
        setPendingRequest(data.pendingRequest);
        setIsFriend(data?.isFriend ?? true);
    }

    async function contact() {
        const response = await fetch(`${domain}/conversations`, {
            credentials: "include",
            method : 'POST' ,
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            },
            body : JSON.stringify({user2_id : user_id})
        });

        const data = await response.json() ;
        console.log(data)
    }

    async function logout() {
        const response = await fetch(`${domain}/logout`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
        if (response.ok) router.push('/auth/login');
    }

    async function banUser() {
        const response = await fetch(`${domain}/users/${user.id}/ban`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
    }

    async function addFriend() {
        const response = await fetch(`${domain}/requests`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            },
            body: JSON.stringify({ receiver_id: user.id })
        });

        const data = await response.json();
    }

    async function rejectFriendRequest() {
        const response = await fetch(`${domain}/requests/${pendingRequest.id}/reject`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
    }

    async function acceptFriendRequest() {
        const response = await fetch(`${domain}/requests/${pendingRequest.id}/accept`, {
            method: 'POST',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
    }

    async function canselFriendRequest() {
        const response = await fetch(`${domain}/requests/${pendingRequest.id}`, {
            method: 'DELETE',
            credentials: "include",
            headers: {
                "Accept": "application/json",
                "content-type": "application/json",
            }
        });

        const data = await response.json();
    }


    useEffect(() => {
        fetchUser(user_id)
    }, []);


    return (
        <section className="mx-auto w-full max-w-6xl">

            <div className="w-full flex items-center justify-center">
                <div className="flex flex-col items-end">

                    <div className="xl:w-[60vw] lg:w-[80vw] mb-6 rounded-2xl border border-white/10 bg-[#151b23] p-3 shadow-lg">
                        <div className="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between ">

                            <div className=" flex flex-1 items-start gap-5 ">

                                <div className="flex justify-center md:justify-between items-center w-full flex-wrap ">
                                    <div className="flex gap-20 items-center justify-center min-w-[300px] md:max-w-[70%] ">

                                        <div className="flex flex-col sm:flex-row gap-4 items-center">
                                            <img src={user?.image?.url || '/images/blank-profile.webp'}
                                                alt={user?.name}
                                                className="w-30 h-30 md:h-40 md:w-40 rounded-full border border-white/20 bg-[#0d1117] object-cover" />

                                            <div className="text-center sm:text-left">
                                                <h2 className="text-2xl font-bold" style={authUser?.role == 'Admin' ? { color: 'yellow' } : authUser?.role == 'Moderator' ? { color: 'red' } : { color: 'white' }}>
                                                    {user?.name || 'fetching ...'}
                                                </h2>
                                                <p className="text-md text-white">{user?.email || 'name : fetching ...'}</p>
                                                <p className="text-md text-white ">Rank : {user?.score || 'fetching ...'}</p>
                                                <p className="text-md text-white ">{user?.bio}</p>
                                            </div>

                                        </div>
                                    </div>

                                    <div className="flex flex-col min-w-[200px]">
                                        <div>
                                            <div className="flex gap-3 sm:grid-cols-2">
                                                <div className="px-4 py-3">
                                                    <p className="mt-2 text-lg text-center text-white">{sentRequests.length}</p>
                                                    <p className="text-xs text-[#9198a1]">Following</p>
                                                </div>
                                                <div className="px-4 py-3">
                                                    <p className="mt-2 text-lg text-center text-white">
                                                        {receivedRequests.length}
                                                    </p>
                                                    <p className="text-xs text-[#9198a1]">Followers</p>
                                                </div>
                                                <div className="px-4 py-3">
                                                    <p className="mt-2 text-lg text-center text-white">{posts.length}</p>
                                                    <p className="text-xs text-[#9198a1]">Posts</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="flex flex-wrap gap-3 items-center">

                        {authUser.id === user?.id &&
                            <>
                                <button onClick={toggleModel}
                                    className="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer">
                                    update
                                </button>

                                <button onClick={logout}
                                    className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                                    logout
                                </button>
                            </>
                        }

                        {user.id != authUser.id &&
                            <button onClick={contact}
                                className="rounded-full border border-red-400/30 bg-red-500/10 p-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                                <svg className="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                    <path fill="#fff" d="M568.4 37.7C578.2 34.2 589 36.7 596.4 44C603.8 51.3 606.2 62.2 602.7 72L424.7 568.9C419.7 582.8 406.6 592 391.9 592C377.7 592 364.9 583.4 359.6 570.3L295.4 412.3C290.9 401.3 292.9 388.7 300.6 379.7L395.1 267.3C400.2 261.2 399.8 252.3 394.2 246.7C388.6 241.1 379.6 240.7 373.6 245.8L261.2 340.1C252.1 347.7 239.6 349.7 228.6 345.3L70.1 280.8C57 275.5 48.4 262.7 48.4 248.5C48.4 233.8 57.6 220.7 71.5 215.7L568.4 37.7z" />
                                </svg>
                            </button>
                        }

                        {(user.role == 'Admin' && user?.id != authUser?.id && user?.role == 'Client') &&
                            <button onClick={banUser}
                                className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                                {authUser?.is_banned || authUser?.is_banned_by_moderator ? 'unban' : 'ban'}
                            </button>
                        }

                        {/* after testing , change the conditions and use one button to ban / unban */}
                        {(user.role == 'Moderator' && user?.id != authUser?.id && user?.role == 'Client') &&
                            <button onClick={banUser}
                                className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                                {authUser?.is_banned_by_moderator ? 'unban' : 'temp ban'}
                            </button>
                        }

                        {user?.is_banned &&
                            <button disabled
                                className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                baned by admin
                            </button>
                        }

                        {(pendingRequest && pendingRequest?.status == 'pending') &&
                            <section>

                                {pendingRequest?.sender_id == authUser.id &&
                                    <button onClick={canselFriendRequest}
                                        className="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer">
                                        cansel
                                    </button>
                                }

                                {pendingRequest?.receiver_id === authUser.id &&
                                    <div className="flex flex-wrap gap-3">
                                        <button onClick={rejectFriendRequest}
                                            className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                                            reject
                                        </button>
                                        <button onClick={acceptFriendRequest}
                                            className="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer">
                                            accept
                                        </button>
                                    </div>
                                }

                            </section>
                        }

                        {(!isFriend && authUser?.id != user.id && !pendingRequest) &&
                            <button onClick={addFriend}
                                className="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer">
                                add friend
                            </button>
                        }

                        {/* {user?.id === authUser.id && 
                            <button
                                className="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20 cursor-pointer">
                                delete my acc
                            </button>
                        } */}

                    </div>

                </div>
            </div>

            <section className="rounded py-2">

                {authUser.id === user?.id &&
                    <div className="mb-6 flex items-center justify-between gap-4">
                        <Link title='add post' href='/main/community/posts' title='create Post'
                            className="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                            <span>+</span>
                        </Link>
                    </div>
                }

                <div className="flex flex-col gap-6 mt-10 items-center">
                    {posts.length ?
                        posts.map((post) => <Post key={post.id} post={post} setPosts={setPosts} creator={user} Container={CommentContainer} />)
                        :
                        <div
                            className="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                            no posts yet
                        </div>
                    }
                </div>
            </section>

            {isModelOpen ?
                <div className="absolute top-0 left-0 w-screen h-screen flex items-center justify-center z-20 backdrop-blur-xs">
                    <div className="z-[13]">
                        <EditProfileModel setIsModelOpen={setIsModelOpen} />
                    </div>
                    <div className='absolute top-0 left-0 w-full h-full bg-black/50' onClick={toggleModel}></div>
                </div>
                : ''}

        </section>
    )
}


