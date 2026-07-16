import "./globals.css";
import AppProvider  from "@/context/AppContext.jsx";
import NavProvider  from "@/context/NavContext.jsx";
import NotificationProvider  from "@/context/NotificationContext.jsx";


export default function RootLayout({ children }: Readonly<{ children: React.ReactNode; }>) {

  return (

    <html lang="en">

      <AppProvider>
        <NavProvider>
        <NotificationProvider>
        {children}
        </NotificationProvider>
        </NavProvider>
      </AppProvider>
      
    </html>
  );
}
