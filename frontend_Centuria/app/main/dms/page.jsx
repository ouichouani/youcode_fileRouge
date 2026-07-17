
import Discussions from "@/components/dms/Discussions";
import ChatList from "@/components/dms/ChatList";
import DiscussionProvider from "@/context/DiscussionContext.jsx";

export default function DMSPAge() {

    return (
        <DiscussionProvider>

            <section className="mx-auto w-full h-full flex justify-center">

                <div className="flex justify-c enter gap-2 h-full flex-1">

                    <section className="resize-x min-w-[20vw] w-[30vw] md:max-w-[50vw] border border-white/10 rounded-lg flex gap-2 flex-col overflow-auto  resize-left">
                        <ChatList />
                    </section>

                    <section className="hidden md:block w-full h-full">
                        <Discussions />
                    </section>
                </div>

            </section>
        </DiscussionProvider>
    )
}