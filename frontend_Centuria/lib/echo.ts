import Echo from "laravel-echo";
import Pusher from "pusher-js";

export default function getEcho() {
    if (typeof window === "undefined") return null;

    window.Pusher = Pusher;
    const token = getCookie("token");


    return new Echo({
        broadcaster: "reverb",
        key: process.env.NEXT_PUBLIC_REVERB_APP_KEY!,
        wsHost: process.env.NEXT_PUBLIC_REVERB_HOST!,
        wsPort: Number(process.env.NEXT_PUBLIC_REVERB_PORT),
        wssPort: Number(process.env.NEXT_PUBLIC_REVERB_PORT),
        forceTLS: false,
        enabledTransports: ["ws"],

        authEndpoint: "http://localhost:80/broadcasting/auth",
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        },
    });
}

function getCookie(name: string) {
    if (typeof document === "undefined") return null;

    return document.cookie
        .split("; ")
        .find((row) => row.startsWith(`${name}=`))
        ?.split("=")[1];
}