"use client";
import { createContext, useEffect, useState } from "react";
import { usePathname } from "next/navigation";
import getEcho from "@/lib/echo";

export const AppContext = createContext({});

export default function AppProvider({ children }) {

  const domain = process.env.NEXT_PUBLIC_API_DOMAIN ;

  // currentConversation USED TO KNOW THE CURRENT CONVERSATION
  const [currentDiscussion, setCurrentDiscussion] = useState({ discussion : null, user: null });

  // LIST OF ONLINE USERS USED IN CHALLIST IN DMS PAGE
  const [onlineUsers, setOnlineUsers] = useState({});

  // THE AUTH USER
  const [globalUser, setGlobalUser] = useState({});

  // avoid the error server : ReferenceError: localStorage is not defined
  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (!storedUser || storedUser === "undefined") return;

    try {
      setGlobalUser(JSON.parse(storedUser));
    } catch (err) {
      console.error("Invalid user in localStorage:", storedUser);
      localStorage.removeItem("user");
    }
  }, []);

  const setUser = (item) => {
    localStorage.setItem('user', JSON.stringify(item));
    setGlobalUser((prev) => ({ ...prev, ...item }));
  }

  // TEMP NOTIFICATION GLOBAL STATE
  const [toastNotification, setToastNotification] = useState([]);

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

  const pathname = usePathname();

  let pagetitle = ''
  switch (true) {
    case pathname.includes("followers"): pagetitle = 'manage followers'; break
    case pathname.includes("following"): pagetitle = 'manage following'; break
    case pathname.includes("inbox"): pagetitle = 'manage requests'; break
    case pathname.includes("tasks"): pagetitle = 'manage tasks'; break
    case pathname.includes("habits"): pagetitle = 'manage habits'; break
    case pathname.includes("logs"): pagetitle = 'manage logs'; break
    case pathname.includes("profile"): pagetitle = 'manage profile'; break
    case pathname.includes("board"): pagetitle = 'traking board'; break
    case pathname.includes("categories"): pagetitle = 'manage categories'; break
    case pathname.includes("requests"): pagetitle = 'manage requests'; break
    case pathname.includes("history"): pagetitle = 'manage history'; break
    case pathname.includes("login"): pagetitle = 'login'; break
    case pathname.includes("explore"): pagetitle = 'explore'; break
    case pathname.includes("register"): pagetitle = 'register'; break
    case pathname.includes("controll-panel"): pagetitle = 'controll-panel'; break
    default: pagetitle = 'Centuria'; break
  }

  useEffect(() => {

    // UPDATE THE LAST SEEN EVERY 30S FOR THE AUTH USER
    const interval = setInterval(async () => {
      await fetch(`${domain}/users/ping`, {
        method: "POST",
        credentials: "include",
      });
    }, 30000); // every 30 seconds


    const echo = getEcho();
    const ONLINE_CHANNEL = echo.join(`online-users`);

    ONLINE_CHANNEL.subscribed(() => {
      // INDECATOR OF JOINING
      console.log('YOU ARE ONLINE ...  ');

    }).here(users => {
      // GET ONLINE USERS INTO THE STATE 
      const currentOnlineUsers = {};
      users.forEach(item => { currentOnlineUsers[item.id] = item });
      setOnlineUsers(prev => ({ ...prev, ...currentOnlineUsers }));

    }).joining(user => {
      // ADD THE JOINED USER TO THE STATE 
      setOnlineUsers(prev => ({ ...prev, [user.id]: user }));

    }).leaving((leavingUser) => {
      // REMOVE THE USER FROM THE STATE
      setOnlineUsers(prev => {
        const updated = { ...prev };
        delete updated[leavingUser.id];
        return updated;
      })

    }).error((error) => {
      // SHOW IF ANY ERROR IN THIS CHANNEL
      console.log("ONLINE USERS CHANNEL ERROR:", error);

    });

    return () => {
      clearInterval(interval);
      echo.leave(`online-users`);
    }

  }, []);


  const sharedValues = {
    user: globalUser,
    setUser: setUser,

    pathname: pathname,
    pagetitle: pagetitle,

    toastNotification: toastNotification,
    notify: notify,

    currentDiscussion: currentDiscussion,
    setCurrentDiscussion: setCurrentDiscussion,

    onlineUsers: onlineUsers,
  }

  return (
    <AppContext.Provider value={{ ...sharedValues }} >
      {children}
    </AppContext.Provider>
  )
}