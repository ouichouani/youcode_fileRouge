"use client";
import Link from "next/link";
import { AppContext } from '@/context/AppContext.jsx'
import { useContext, useEffect, useRef } from "react";
import { NavContext } from '@/context/NavContext.jsx'



export default function Nav() {

    const { user, pathname, pagetitle } = useContext(AppContext);

    // nav SHOULD BE AN ARRAY OF OBJECT . EACH OBJECT SHOULD INCLUD title ,href  AND imageSrc
    const { nav } = useContext(NavContext);
    

    const BORDERDIVSTYLE = {
        width: '20px',
        height: '20px',
        mask: "radial-gradient(circle at bottom left ,#0000 20px ,#000 20px)",
        pointerEvents: 'none',
        trunsitionDuration: '10000ms',
        background: '#151b23',
    }


    return (

        <nav className="relative bg-[#151b23] py-3 group max-w-[60px] bg-yel low-500 p-[7px] min-w-fit flex flex-col gap-1 transition-all duration-300 rounded-lg">
            <div className="absolute top-[-20px] right-[40px] rotate-90 opacity-100 group-hover:opacity-100 group-hover:right-[60px] duration-500 transition-all " style={BORDERDIVSTYLE}></div>

            {Array.isArray(nav) && nav?.map((item, key) =>
                <Link key={key} className={`${pathname.includes(item.href) ? 'bg-[#212830] ' : ''} flex items-center gap-0 group-hover:gap-3 rounded-lg p-2 transition-all duration-300 hover:bg-[#212830] `} href={`${item.href}`}>
                    <div className="w-[25px] h-[25px]" >
                        <img src={item.imageSrc} alt="" className='w-full h-full hrink-0' />
                    </div>
                    <span className="max-w-0 text-nowrap opacity-0 overflow-hidden group-hover:opacity-100 group-hover:max-w-[200px] md:group-hover:max-w-[10vw] transition-all duration-300">{item.title}</span>
                </Link>
            )}

            <div className="absolute bottom-[-20px] right-[40px]  opacity-100 group-hover:opacity-100 group-hover:right-[60px] transition-all duration-500 " style={BORDERDIVSTYLE}></div>
        </nav>

    )
}