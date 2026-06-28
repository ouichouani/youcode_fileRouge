'use client';
import { useRef, useState } from "react"

export default function DMSPAge() {


    const [message, setMessage] = useState('');
    const openDms = useRef(true);

    function handleChange(e) {
        // ...
    }

    function fetch_list_dms() {
        // ...
    }

    function fetch_lessages($user_ic) {
        // ...
    }

    function send_message() {
        // ...
    }

    const messages = [
        { id: 1, message: "Hello there!", sender_id: 1, receiver_id: 10 },
        { id: 2, message: "Hi!", sender_id: 10, receiver_id: 1 },
        { id: 3, message: "How are you?", sender_id: 1, receiver_id: 10 },
        { id: 4, message: "I'm doing well, thanks.", sender_id: 10, receiver_id: 1 },
        { id: 5, message: "What are you doing?", sender_id: 1, receiver_id: 10 },
        { id: 6, message: "Just working.", sender_id: 10, receiver_id: 1 },
        { id: 7, message: "That's nice.", sender_id: 1, receiver_id: 10 },
        { id: 8, message: "Yes, keeping busy.", sender_id: 10, receiver_id: 1 },
        { id: 9, message: "Want to grab coffee?", sender_id: 1, receiver_id: 10 },
        { id: 10, message: "Sure, sounds good.", sender_id: 10, receiver_id: 1 },
        { id: 11, message: "See you at 5.", sender_id: 1, receiver_id: 10 },
        { id: 12, message: "I'll be there.", sender_id: 10, receiver_id: 1 },
        { id: 13, message: "Don't be late.", sender_id: 1, receiver_id: 10 },
        { id: 14, message: "I won't.", sender_id: 10, receiver_id: 1 },
        { id: 15, message: "Did you finish the project?", sender_id: 1, receiver_id: 10 },
        { id: 16, message: "Almost done.", sender_id: 10, receiver_id: 1 },
        { id: 17, message: "Need any help?", sender_id: 1, receiver_id: 10 },
        { id: 18, message: "Maybe a little.", sender_id: 10, receiver_id: 1 },
        { id: 19, message: "I'm free today.", sender_id: 1, receiver_id: 10 },
        { id: 20, message: "Perfect!", sender_id: 10, receiver_id: 1 },
        { id: 21, message: "Let's start now.", sender_id: 1, receiver_id: 10 },
        { id: 22, message: "Okay.", sender_id: 10, receiver_id: 1 },
        { id: 23, message: "Everything ready?", sender_id: 1, receiver_id: 10 },
        { id: 24, message: "Yes, all set.", sender_id: 10, receiver_id: 1 },
        { id: 25, message: "Awesome!", sender_id: 1, receiver_id: 10 },
        { id: 26, message: "Let's go.", sender_id: 10, receiver_id: 1 },
        { id: 27, message: "Where are you?", sender_id: 1, receiver_id: 10 },
        { id: 28, message: "On my way.", sender_id: 10, receiver_id: 1 },
        { id: 29, message: "Drive safely.", sender_id: 1, receiver_id: 10 },
        { id: 30, message: "Will do.", sender_id: 10, receiver_id: 1 },
        { id: 31, message: "Did you eat?", sender_id: 1, receiver_id: 10 },
        { id: 32, message: "Not yet.", sender_id: 10, receiver_id: 1 },
        { id: 33, message: "Let's order food.", sender_id: 1, receiver_id: 10 },
        { id: 34, message: "Great idea.", sender_id: 10, receiver_id: 1 },
        { id: 35, message: "Pizza?", sender_id: 1, receiver_id: 10 },
        { id: 36, message: "I prefer burgers.", sender_id: 10, receiver_id: 1 },
        { id: 37, message: "That's fine.", sender_id: 1, receiver_id: 10 },
        { id: 38, message: "Thanks!", sender_id: 10, receiver_id: 1 },
        { id: 39, message: "How's the weather?", sender_id: 1, receiver_id: 10 },
        { id: 40, message: "It's sunny today.", sender_id: 10, receiver_id: 1 },
        { id: 41, message: "Nice!", sender_id: 1, receiver_id: 10 },
        { id: 42, message: "Perfect for a walk.", sender_id: 10, receiver_id: 1 },
        { id: 43, message: "Let's go later.", sender_id: 1, receiver_id: 10 },
        { id: 44, message: "Sure thing.", sender_id: 10, receiver_id: 1 },
        { id: 45, message: "Did you watch the game?", sender_id: 1, receiver_id: 10 },
        { id: 46, message: "Yes, it was amazing!", sender_id: 10, receiver_id: 1 },
        { id: 47, message: "Best match ever.", sender_id: 1, receiver_id: 10 },
        { id: 48, message: "I agree.", sender_id: 10, receiver_id: 1 },
        { id: 49, message: "What's next?", sender_id: 1, receiver_id: 10 },
        { id: 50, message: "Let's relax.", sender_id: 10, receiver_id: 1 },
        { id: 51, message: "Sounds good.", sender_id: 1, receiver_id: 10 },
        { id: 52, message: "Absolutely.", sender_id: 10, receiver_id: 1 },
        { id: 53, message: "Any plans tomorrow?", sender_id: 1, receiver_id: 10 },
        { id: 54, message: "Not really.", sender_id: 10, receiver_id: 1 },
        { id: 55, message: "Let's hang out.", sender_id: 1, receiver_id: 10 },
        { id: 56, message: "I'm in.", sender_id: 10, receiver_id: 1 },
        { id: 57, message: "Cool.", sender_id: 1, receiver_id: 10 },
        { id: 58, message: "See you then.", sender_id: 10, receiver_id: 1 },
        { id: 59, message: "Bring your laptop.", sender_id: 1, receiver_id: 10 },
        { id: 60, message: "I will.", sender_id: 10, receiver_id: 1 },
        { id: 61, message: "Need anything else?", sender_id: 1, receiver_id: 10 },
        { id: 62, message: "No, thanks.", sender_id: 10, receiver_id: 1 },
        { id: 63, message: "Good morning!", sender_id: 1, receiver_id: 10 },
        { id: 64, message: "Morning!", sender_id: 10, receiver_id: 1 },
        { id: 65, message: "Ready for today?", sender_id: 1, receiver_id: 10 },
        { id: 66, message: "Always.", sender_id: 10, receiver_id: 1 },
        { id: 67, message: "Let's do it.", sender_id: 1, receiver_id: 10 },
        { id: 68, message: "Let's go!", sender_id: 10, receiver_id: 1 },
        { id: 69, message: "Everything okay?", sender_id: 1, receiver_id: 10 },
        { id: 70, message: "Yes, everything's fine.", sender_id: 10, receiver_id: 1 },
        { id: 71, message: "Good to hear.", sender_id: 1, receiver_id: 10 },
        { id: 72, message: "Thanks for asking.", sender_id: 10, receiver_id: 1 },
        { id: 73, message: "Have you finished?", sender_id: 1, receiver_id: 10 },
        { id: 74, message: "Just now.", sender_id: 10, receiver_id: 1 },
        { id: 75, message: "Excellent.", sender_id: 1, receiver_id: 10 },
        { id: 76, message: "Thank you.", sender_id: 10, receiver_id: 1 },
        { id: 77, message: "Any updates?", sender_id: 1, receiver_id: 10 },
        { id: 78, message: "Nothing new.", sender_id: 10, receiver_id: 1 },
        { id: 79, message: "Keep me posted.", sender_id: 1, receiver_id: 10 },
        { id: 80, message: "Will do.", sender_id: 10, receiver_id: 1 },
        { id: 81, message: "See you tomorrow.", sender_id: 1, receiver_id: 10 },
        { id: 82, message: "See you!", sender_id: 10, receiver_id: 1 },
        { id: 83, message: "Take care.", sender_id: 1, receiver_id: 10 },
        { id: 84, message: "You too.", sender_id: 10, receiver_id: 1 },
        { id: 85, message: "Good night.", sender_id: 1, receiver_id: 10 },
        { id: 86, message: "Sleep well.", sender_id: 10, receiver_id: 1 },
        { id: 87, message: "Talk tomorrow.", sender_id: 1, receiver_id: 10 },
        { id: 88, message: "Sure.", sender_id: 10, receiver_id: 1 },
        { id: 89, message: "Bye!", sender_id: 1, receiver_id: 10 },
        { id: 90, message: "Bye bye!", sender_id: 10, receiver_id: 1 },
        { id: 91, message: "Have a nice weekend.", sender_id: 1, receiver_id: 10 },
        { id: 92, message: "You too!", sender_id: 10, receiver_id: 1 },
        { id: 93, message: "Let's catch up soon.", sender_id: 1, receiver_id: 10 },
        { id: 94, message: "Definitely.", sender_id: 10, receiver_id: 1 },
        { id: 95, message: "Don't forget the meeting.", sender_id: 1, receiver_id: 10 },
        { id: 96, message: "I won't forget.", sender_id: 10, receiver_id: 1 },
        { id: 97, message: "See you there.", sender_id: 1, receiver_id: 10 },
        { id: 98, message: "Looking forward to it.", sender_id: 10, receiver_id: 1 },
        { id: 99, message: "Have a great day!", sender_id: 1, receiver_id: 10 },
        { id: 100, message: "Thanks, you too!", sender_id: 10, receiver_id: 1 }
    ];

    const contactAccount = { id: 10 };
    const user = { id: 1 };
    const users = [6, 5, 4, 3, 2, 1];
    const dms = [
        { id: 1, name: "Ali", lastMessage: "Hey, how are you?" },
        { id: 2, name: "Sara", lastMessage: "See you tomorrow!" },
        { id: 3, name: "Omar", lastMessage: "Don't forget the meeting." },
        { id: 4, name: "Youssef", lastMessage: "I'm on my way." },
        { id: 5, name: "Fatima", lastMessage: "Thanks a lot!" },
        { id: 6, name: "Amina", lastMessage: "Can you call me?" },
        { id: 7, name: "Khalid", lastMessage: "Everything is ready." },
        { id: 8, name: "Nadia", lastMessage: "Let's grab some coffee." },
        { id: 9, name: "Hamza", lastMessage: "I'll text you later." },
        { id: 6, name: "Amina", lastMessage: "Can you call me?" },
        { id: 7, name: "Khalid", lastMessage: "Everything is ready." },
        { id: 8, name: "Nadia", lastMessage: "Let's grab some coffee." },
        { id: 9, name: "Hamza", lastMessage: "I'll text you later." },
        { id: 10, name: "Meryem", lastMessage: "Good night!" }
    ];

    return (
        <section className="mx-auto w-full h-full flex justify-center  bg-gre en-500">

            <div className="flex gap-2 h-full bg-red -500 flex-1">

                <section className="pr-3 flex gap-2 flex-col overflow-auto w-full md:max-w-[30vw]">
                    {dms?.map((item, key) =>
                        <div key={key} className="transition-all duration-300 bg-[#151b23] hover:bg-[#212830] rounded-lg flex items-center gap-4 p-2">
                            <div>
                                <img
                                    src="/images/blank-profile.webp"
                                    alt="contact profie"
                                    className="w-13 h-13 rounded-full"
                                />
                            </div>

                            <div>
                                <h1>{item.name}</h1>
                                <p>{item.lastMessage}</p>
                            </div>
                        </div>
                    )}
                </section>

                <section className="hidden md:w-[70vw] md:block w-full h-full">
                    {openDms && (
                        <div className="h-full flex flex-col border border-white/10 rounded-lg overflow-hidden">

                            <section className="bg-[#151b23] flex items-center gap-4 p-2">
                                <div>
                                    <img
                                        src="/images/blank-profile.webp"
                                        alt="contact profie"
                                        className="w-13 h-13 rounded-full"
                                    />
                                </div>

                                <div>
                                    <h1>Olivar sama</h1>
                                    <p>last seen lbare7</p>
                                </div>
                            </section>

                            <section className="flex-1 flex flex-col gap-1 p-10 overflow-auto bg-yel low-500">
                                {messages.map(item =>

                                    <div key={item.id} className={`max-w-[70%] self-end rounded-lg ${item.sender_id == user.id ? "bg-[#212830] self-end" : "bg-green-800 self-start"} `}>
                                        <p className="py-2 px-6">{item.message}</p>
                                        {/* <p>{item.created_at}</p> */}
                                    </div>
                                )}
                            </section>

                            <form className="flex gap-1 items-center py-5 px-2 ">
                                <input type="text" name='content' placeholder="comment" required
                                    className="w-full p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 " />
                                <button>
                                    <svg className="cursor-pointer transition hover:text-blue-400" width="25px"
                                        height="25px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" strokeWidth="0" />
                                        <g id="SVGRepo_tracerCarrier" strokeLinecap="round" strokeLinejoin="round" />
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M10.3009 13.6949L20.102 3.89742M10.5795 14.1355L12.8019 18.5804C13.339 19.6545 13.6075 20.1916 13.9458 20.3356C14.2394 20.4606 14.575 20.4379 14.8492 20.2747C15.1651 20.0866 15.3591 19.5183 15.7472 18.3818L19.9463 6.08434C20.2845 5.09409 20.4535 4.59896 20.3378 4.27142C20.2371 3.98648 20.013 3.76234 19.7281 3.66167C19.4005 3.54595 18.9054 3.71502 17.9151 4.05315L5.61763 8.2523C4.48114 8.64037 3.91289 8.83441 3.72478 9.15032C3.56153 9.42447 3.53891 9.76007 3.66389 10.0536C3.80791 10.3919 4.34498 10.6605 5.41912 11.1975L9.86397 13.42C10.041 13.5085 10.1295 13.5527 10.2061 13.6118C10.2742 13.6643 10.3352 13.7253 10.3876 13.7933C10.4468 13.87 10.491 13.9585 10.5795 14.1355Z"
                                                stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                                strokeLinejoin="round" />
                                        </g>
                                    </svg>
                                </button>

                            </form>


                        </div>
                    )}
                </section>
            </div>

        </section>
    )
}