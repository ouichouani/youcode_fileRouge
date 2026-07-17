"use client";
import Link from "next/link";
import { AppContext } from '@/context/AppContext.jsx'
import { useContext, useEffect, useRef } from "react";


export default function SideBar() {

    const { user, pathname, pagetitle } = useContext(AppContext);
    const BORDERDIVSTYLE = {
        width: '20px',
        height: '20px',
        mask: "radial-gradient(circle at bottom right ,#0000 20px ,#000 20px)",
        pointerEvents : 'none' ,
        trunsitionDuration : '10000ms' ,
        background : '#151b23' ,
    }


    return (
        <>
            <aside
                data-sidebar
                className="sticky inset-y-0 left-0 z-10 w-[60px] min-w-[60px] bg-[#151b23] pt-40 text-white shadow-2xl border-white/30 border-solid transition-transform duration-200">


                <div className="w-full h-full relative" >


                    <ul className="bg-[#151b23] bg-red -500 py-3 group w-[60px] px-[10px] min-w-fit flex flex-col gap-2 relative left-[1 0px] transition-all duration-300 rounded-lg">
                        <div className="absolute top-[-20px] left-[40px]  -rotate-90 opacity-100 group-hover:opacity-100 group-hover:left-[60px] duration-500 transition-all " style={BORDERDIVSTYLE}></div>


                        <Link className={`${pathname.includes('/dashboard') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830] `} href={'/main/dashboard/board'}>
                            <div className="w-[25px] h-[25px]" >
                                <img src="/svg/dashboard.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">dashboard</span>
                        </Link>

                        <Link className={`${pathname.includes('/community') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830] `} href={'/main/community'}>
                            <div className="w-[25px] h-[25px]" >
                                <img src="/svg/explore.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">community</span>
                        </Link>

                        <Link className={`${pathname.includes('/profile') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830]`} href={'/main/profile'}>
                            <div className="w-[25px] h-[25px]" >
                                <img src="/svg/profile.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">profile</span>
                        </Link>

                        <Link className={`${pathname.includes('/notification') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830] `} href={'/main/notifications'}>
                            <div className="w-[25px] h-[25px]" >
                                <img src="/svg/notification.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">notification</span>
                        </Link>

                        <Link className={`${pathname.includes('/requests') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830]`} href={'/main/requests'}>
                            <div className="w-[25px] h-[25px]" >
                                <img src="/svg/requests.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">requests</span>
                        </Link>

                        <Link className={`${pathname.includes('/dms') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830] `} href={'/main/dms'}>
                            <div className="w-[25px] h-[25px] " >
                                <img src="/svg/dms.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">messages</span>
                        </Link>

                        <Link className={`${pathname.includes('/controll-panel') ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830] `} href={'/main/controll-panel'}>
                            <div className="w-[25px] h-[25px] " >
                                <img src="/svg/settings.svg" alt="" className='w-full h-full hrink-0' />
                            </div>
                            <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">controll-panel</span>
                        </Link>


                        <div className="absolute bottom-[-20px] left-[40px]  opacity-100 group-hover:opacity-100 group-hover:left-[60px] transition-all duration-500 " style={BORDERDIVSTYLE}></div>
                    </ul>



                    <div className="absolute bottom-10 left-[50%] -translate-x-[50%] bg-500 w-[35px] h-[35px]">
                        <Link href={"/"}>
                            <img src="/svg/general-setting.svg" alt="general-setting" className="w-full h-full hrink-0 hover:rotate-360deg transition-transform duration-500 hover:rotate-180" />
                        </Link>
                    </div>
                </div>



            </aside>

            <button
                className="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"
                aria-label="Close menu">
            </button>
        </>
    )
}