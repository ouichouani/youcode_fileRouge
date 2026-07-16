'use client';

import Link from "next/link";
import { AppContext } from '@/context/AppContext.jsx'
import { useContext, useEffect } from 'react';
import Nav from "@/components/layout/Nav.jsx";
import { NavContext } from '@/context/NavContext.jsx'
import TostsComponent from "@/components/layout/TostsComponent.jsx";

export default function Header() {
    const { user, pathname, pagetitle } = useContext(AppContext);
    const { nav } = useContext(NavContext);


    return (
        <header
            className="fixed bg-red-5 00 w-[60px] h-fit  py-2 right-0 top-0 rounded-bl-xl border-b border-white/30 border-solid bg-[#151b23] shadow-lg z-10">
            {/* <div className="h-full  fl ex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"> */}

                {/* <div className="flex items-center gap-3 ">
                    <h1 className="text-xl font-bold tracking-wide text-white">
                        {pagetitle.toUpperCase()}
                    </h1>
                </div> */}

                <div className="h-full w-full flex flex-col items-end gap-10 py-5">

                    <div className="w-full flex items-center justify-center">
                        <Link href="/main/profile">
                            <img src={user?.image?.url ?? '/images/blank-profile.webp'} alt="profile image"
                                className='w-[40px] shrink-0 aspect-square rounded-full object-cover ' />
                        </Link>
                    </div>

                    {(Array.isArray(nav) && nav?.length > 0) &&
                    <div className="min-w-full flex item-center justify-center">
                        <Nav />
                    </div>
                    }

                </div>

                <TostsComponent/>


            {/* </div> */}


        </header>
    )
}