"use client";
import { createContext, useEffect, useState , useContext } from "react";
import { usePathname } from "next/navigation";
import { AppContext } from "@/context/AppContext";
import getEcho from "@/lib/echo";

export const NotificationContext = createContext({});

export default function NotificationProvider({ children }) {

  // TEMP NOTIFICATION GLOBAL STATE
  const [toastNotification, setToastNotification] = useState([]);
  const { user } = useContext(AppContext);

  // INSERT AN OBJECT IN THE TOAST STATE . IT NEES A MESSAGE
  function notify(message, color = 'green', duration = 3000) {

    const id = new Date().getTime();
    setToastNotification(prev => ([...prev, { removing: false, id: id, message: message, color: color }]));

    setTimeout(() => {

      setToastNotification(prev =>
        prev.map(item =>
          item.id === id
            ? { ...item, removing: true }
            : item
        )
      );

      setTimeout(() => {
        setToastNotification(prev => prev.filter(item => item.id != id));
      }, 500);

    }, duration);

  }

  // SUBSCRIBE TO THE PERSONAL CHANNEL
  useEffect(() => {

    const echo = getEcho();
    const ONLINE_CHANNEL = echo.private(`users.${user.id}`);

    ONLINE_CHANNEL.subscribed(() => {
      // INDECATOR OF JOINING
      console.log('YOU ARE ONLINE ON YOUR CHANNEL  ...  ');

    })
    .listen('friend.request.recieved' , (e)=>{
        // notify
    })
    .listen('post.liked' , (e)=>{
        // notify
    })
    .listen('comment.created' , (e)=>{
        // notify
    })
    .listen('messge.recieved' , (e)=>{
        // notify
    })
    .error((error) => {
      // SHOW IF ANY ERROR IN THIS CHANNEL
      console.log("PRIVATE USERS CHANNEL ERROR:", error);

    });

    return () => {
      clearInterval(interval);
      echo.leave(`online-users`);
    }

  }, []);


  const sharedValues = {
    toastNotification: toastNotification,
    notify: notify,
  }

  return (
    <NotificationContext.Provider value={{ ...sharedValues }} >
      {children}
    </NotificationContext.Provider>
  )
}