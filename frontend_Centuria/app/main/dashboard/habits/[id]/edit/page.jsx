"use client";
import Link from 'next/link';
import { useParams, useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { AppContext } from '@/context/AppContext.jsx'
import { useContext } from "react";



export default function UpdateHabit() {

    const { notify } = useContext(AppContext);
    const { id } = useParams();
    const [errors, setError] = useState({});
    const [habit, setHabit] = useState({ frequency: "", priority: "m", difficulty: "m", title: "" })
    const [categories, setCategories] = useState([])
    const domain = process.env.NEXT_PUBLIC_API_DOMAIN;
    const router = useRouter();

    async function getHabit() {
        try {
            const response = await fetch(`${domain}/habits/${id}`, {
                method: "GET",
                credentials: "include",
                headers: {
                    "accept": "Application/json",
                    "Content-Type": "application/json",
                }
            });


            const data = await response.json();
            setHabit(data.habit);
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
        getHabit();
        getCategories();
    }, []);

    function handleChange(e) {
        const { name, value } = e.target;
        setError(prev => ({ ...prev, [name]: "" }));

        if (name == "frequency") {

            let arr = habit.frequency.includes(value) ? habit.frequency.filter(item => item !== value) : [...habit.frequency, value]
            setHabit(prev => ({ ...prev, [name]: [...arr] }))

        } else {
            setHabit(prev => ({ ...prev, [name]: value }))
        }
    }

    async function handlSubmit(e) {
        e.preventDefault();

        try {
            const response = await fetch(`${domain}/habits/${id}`, {
                method: "PUT",
                credentials: "include",
                headers: {
                    "accept": "Application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(habit),
            });


            const data = await response.json();
            console.log(data.errors)
            if (response.ok) {
                notify('habi is updated with success');     
                return router.push(`/main/dashboard/habits/${id}`);
            }
            if (!response.ok) setError(data.errors)

        } catch (error) {
            console.log("error --> ", error)
        }

    }


    return (
        <section className="mx-auto w-full max-w-4xl py-6">
            <div className="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
                <h2 className="text-xl font-bold tracking-wide text-white">Edit habit</h2>
                <p className="mt-2 text-sm text-[#9198a1]">
                    Update your habit details while keeping the same clean app style.
                </p>
            </div>

            <form className="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg" onSubmit={handlSubmit}>

                <div className="grid gap-5 md:grid-cols-2">
                    <div className="md:col-span-2">
                        <label htmlFor="title" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Title</span>
                            <input id="title" type="text" name="title" placeholder="title" value={habit?.title} onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2" />
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.title}</div>
                    </div>

                    <div className="md:col-span-2">
                        <label htmlFor="description" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Description</span>
                            <textarea id="description" name="description" rows="7" placeholder="task or habit description" onChange={handleChange}
                                className="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{habit?.description}</textarea>
                        </label>
                        <div className="mt-2 text-sm text-red-400">{errors?.description}</div>

                    </div>

                    <div>
                        <label htmlFor="difficulty" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Difficulty</span>
                            <select id="priority" name="difficulty" required value={habit?.difficulty} onChange={handleChange}
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
                            <select id="priority" name="priority" required value={habit?.priority} onChange={handleChange}
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

                    <div className="md:col-span-2">
                        <div className="flex flex-col gap-3 rounded-xl border border-white/10 bg-[#0d1117] p-4">
                            <span className="text-sm font-medium text-white">Frequency</span>
                            <section className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Monday')}
                                        value="Monday" className="accent-blue-500" />
                                    Monday
                                </label>
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Tuesday')}
                                        value="Tuesday" className="accent-blue-500" />
                                    Tuesday
                                </label>
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Wednesday')}
                                        value="Wednesday" className="accent-blue-500" />
                                    Wednesday
                                </label>
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Thursday')}
                                        value="Thursday" className="accent-blue-500" />
                                    Thursday
                                </label>
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Friday')}
                                        value="Friday" className="accent-blue-500" />
                                    Friday
                                </label>
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Saturday')}
                                        value="Saturday" className="accent-blue-500" />
                                    Saturday
                                </label>
                                <label className="flex items-center gap-3 rounded-lg border border-white/10 bg-[#151b23] px-3 py-2 text-sm text-white">
                                    <input type="checkbox" name="frequency" onChange={handleChange} checked={habit?.frequency?.includes('Sunday')}
                                        value="Sunday" className="accent-blue-500" />
                                    Sunday
                                </label>
                            </section>
                        </div>

                        <div className="mt-2 text-sm text-red-400">{errors?.frequency}</div>

                    </div>

                    <div className="md:col-span-2">
                        <label htmlFor="category_id" className="flex flex-col gap-2">
                            <span className="text-sm font-medium text-white">Category</span>
                            <select id="category_id" name="category_id" value={habit?.category_id ? habit?.category_id : ""} onChange={handleChange}
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
                </div >

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