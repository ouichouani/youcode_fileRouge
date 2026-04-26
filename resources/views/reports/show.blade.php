@extends('components.layout')

@section('title')
    DASHBOARD
@endsection

@section('content')
    <section class="mx-auto w-full max-w-5xl px-4 py-6">
        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-wide text-white">Report details</h2>
                    <p class="mt-2 text-sm text-[#9198a1]">
                        Review the reported post, its comments, and the report details in one place.
                    </p>
                </div>
                <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Report id</p>
                    <p class="mt-2 text-lg font-semibold text-white">#{{ $report->id }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
            <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <h3 class="text-lg font-semibold text-white">Reported post</h3>
                    <span class="rounded-full border border-white/10 bg-[#0d1117] px-3 py-1 text-sm text-[#9198a1]">
                        Post #{{ $post->id }}
                    </span>
                </div>

                <div class="rounded-2xl border border-white/10 bg-[#0d1117] p-5">
                    <p class="whitespace-pre-line text-sm leading-7 text-white">
                        {{ $post->content ?: 'This post has no text content.' }}
                    </p>
                </div>

                <div class="mt-6">
                    <h4 class="mb-3 text-sm font-medium uppercase tracking-[0.2em] text-[#9198a1]">Comments</h4>
                    <div class="flex flex-col gap-3">
                        @forelse ($comments as $c)
                            <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                                <p class="text-sm text-white">{{ $c->content }}</p>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                                no comments
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-white">Report summary</h3>

                <div class="mt-5 flex flex-col gap-4">
                    <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Reported by</p>
                        <p class="mt-2 text-sm text-white">{{ $user->email }}</p>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Type</p>
                        <p class="mt-2 text-sm capitalize text-white">{{ str_replace('_', ' ', $report->type) }}</p>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Description</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-7 text-white">{{ $report->description }}</p>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
