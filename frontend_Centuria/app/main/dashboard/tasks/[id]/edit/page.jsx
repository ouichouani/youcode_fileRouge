"use client";
import Link from 'next/link';
import { useParams, useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { AppContext } from '@/context/AppContext.jsx'
import { useContext } from "react";



export default function UpdateTask() {

    const { notify } = useContext(AppContext);
    const { id } = useParams();
    const [errors, setError] = useState({});
    const [task, setTask] = useState({ frequency: "", description :"" , priority: "m", difficulty: "m", title: "" , deadline : new Date().toISOString().split('T')[0] })
    const [categories, setCategories] = useState([])
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const router = useRouter();

    async function getTask() {
        try {
            const response = await fetch(`${domain}/tasks/${id}`, {
                method: "GET",
                credentials: "include",
                headers: {
                    "accept": "Application/json",
                    "Content-Type": "application/json",
                }
            });


            const data = await response.json();
            setTask(prev => ({...prev , ...data.task , deadline : new Date(data.task?.deadline).toISOString().split("T")[0]  }));

            console.log(data) ;
        } catch (error) {
            console.log("error --> ", error)
        }
    }

    async function getCategories() {
        const response = await fetch(`${domain}/categories`, {
            method: "GET",
            credentials: "include",
            headers: {
                "accept": "Application/json",
                "Content-Type": "application/json",
            }
        });
        const data = await response.json();
        setCategories(data.categories);
    }

    useEffect(() => {
        getTask();
        getCategories();
    }, []);

    function handleChange(e) {
        const { name, value } = e.target;
        console.log(value) ;
        setTask(prev => ({ ...prev, [name]: value })) ;
        setError(prev => ({ ...prev, [name]: "" }));
    }

    async function handlSubmit(e) {
        e.preventDefault();

        try {
            const response = await fetch(`${domain}/tasks/${id}`, {
                method: "PUT",
                credentials: "include",
                headers: {
                    "accept": "Application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(task),
            });


            const data = await response.json();
            if (response.ok) {
                
                notify('task is updated with success') ;    
                return router.push(`/main/dashboard/tasks/${id}`);
            }
            if (!response.ok) setError(data.errors)

        } catch (error) {
            console.log("error --> ", error)
        }
    }


    return (
        <section className="mx-auto w-full max-w-4xl py-6">
            <div className="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
                <h2 className="text-xl font-bold tracking-wide text-white">Edit task</h2>
                <p className="mt-2 text-sm text-[#9198a1]">
                    Update your task details while keeping the same clean app style.
                </p>
            </div>

            <form className="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg" onSubmit={handlSubmit}>

                <div className="grid gap-5 md:grid-cols-2">
                    <div className="md:col-span-2">
                        <label htmlFor="title" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Title</span>
                            <input id="title" type="text" name="title" placeholder="title" value={task?.title} onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2" />
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.title}</div>
                    </div>

                    <div className="md:col-span-2">
                        <label htmlFor="description" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Description</span>
                            <textarea id="description" name="description" rows="7" placeholder="task or task description" onChange={handleChange} value={task?.description}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2"></textarea>
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.description}</div>
                    </div>

                    <div>
                        <label htmlFor="difficulty" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Difficulty</span>
                            <select id="priority" name="difficulty" required value={task?.difficulty} onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                                <option className="bg-[#151b23] text-white" value="xxs">xxs</option>
                                <option className="bg-[#151b23] text-white" value="xs">xs</option>
                                <option className="bg-[#151b23] text-white" value="s">s</option>
                                <option className="bg-[#151b23] text-white" value="m" > m</option >
                                <option className="bg-[#151b23] text-white" value="l" > l</option >
                                <option className="bg-[#151b23] text-white" value="xl" > xl</option >
                                <option className="bg-[#151b23] text-white" value="xxl" > xxl</option >
                            </select >
                        </label >
                        <div className="mt-2 text-sm text-red-400">{errors?.difficulty}</div>
                    </div >

                    <div>
                        <label htmlFor="priority" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Priority</span>
                            <select id="priority" name="priority" required value={task?.priority} onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                                <option className="bg-[#151b23] text-white" value="xxs">xxs</option>
                                <option className="bg-[#151b23] text-white" value="xs">xs</option>
                                <option className="bg-[#151b23] text-white" value="s">s</option>
                                <option className="bg-[#151b23] text-white" value="m" > m</option >
                                <option className="bg-[#151b23] text-white" value="l" > l</option >
                                <option className="bg-[#151b23] text-white" value="xl" > xl</option >
                                <option className="bg-[#151b23] text-white" value="xxl" > xxl</option >
                            </select >
                        </label >
                        <div className="mt-2 text-sm text-red-400">{errors?.priority}</div>
                    </div >


                    <div>
                        <label htmlFor="deadline" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Deadline</span>
                            <input id="deadline" type="date" name="deadline" value={task?.deadline}  required onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2" />
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.deadline}</div>
                    </div>


                    <div >
                        <label htmlFor="category_id" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Category</span>
                            <select id="category_id" name="category_id" value={task?.category_id ? task?.category_id : ""} onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">

                                <option className="bg-[#151b23] text-white" value=""> no category </option>
                                {categories?.map((category, key) =>
                                    <option key={category, key} className="bg-[#151b23] text-white" value={category.id}>
                                        {category.title}
                                    </option>
                                )}
                            </select>
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.category_id}</div>
                    </div >
                </div>

                <div className="mt-8 flex justify-end">
                    <button
                        className="rounded-lg border border-white/20 bg-[#0d1117] px-6 py-2 text-sm font-medium text-white transition hover:bg-green-500/20">
                        update
                    </button>
                </div>

            </form >
        </section >

    )

}