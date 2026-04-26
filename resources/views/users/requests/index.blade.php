@extends('components.layout')

@section('title')
    REQUESTS
@endsection

@section('content')
    <section class="mx-auto w-full max-w-5xl px-4 py-6">
        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold tracking-wide text-white">Friend requests</h2>
                    <p class="mt-2 text-sm text-[#9198a1]">
                        Review incoming requests and decide who you want to accept, reject, or remove.
                    </p>
                </div>
                <div class="rounded-xl border border-white/10 bg-[#0d1117] px-4 py-3">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#9198a1]">Total requests</p>
                    <p class="mt-2 text-lg font-semibold text-white">{{ count($friendRequests) }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-5">
            @forelse ($friendRequests as $f)
                <article class="rounded-2xl border border-white/10 bg-[#151b23] p-5 shadow-lg">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start gap-4">
                            <img src="{{ asset($f->sender->image?->path ? 'storage/' . $f->sender->image->path : 'images/blank-profile.webp') }}"
                                alt="{{ $f->sender->name }}"
                                class="h-16 w-16 rounded-full border border-white/20 bg-[#0d1117] object-cover">

                            <div>
                                <a href="{{ route('users.show', $f->sender->id) }}">
                                    <p class="text-lg font-semibold text-white">{{ $f->sender->name }}</p>
                                    <p class="mt-1 text-sm text-[#9198a1]">{{ $f->sender->email }}</p>
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if ($f->status == 'pending')
                                <form action="{{ route('requests.accept', $f->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                        accept
                                    </button>
                                </form>

                                <form action="{{ route('requests.reject', $f->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                        reject
                                    </button>
                                </form>
                            @endif

                            @if ($f->status == 'rejected')
                                <form action="{{ route('requests.destroy', $f->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="rounded-lg border border-white/10 bg-[#151b23] px-5 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                                        delete
                                    </button>
                                </form>
                            @endif
                            @if ($f->status == 'accepted')
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <span
                                        class="rounded-full border px-3 py-1 text-sm {{ $f->status === 'pending' ? 'border-yellow-400/20 bg-yellow-500/10 text-yellow-200' : ($f->status === 'accepted' ? 'border-green-400/20 bg-green-500/10 text-green-200' : 'border-red-400/20 bg-red-500/10 text-red-200') }}">
                                        {{ $f->status }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-white/15 bg-[#151b23] p-8 text-center shadow-lg">
                    <p class="text-base text-[#9198a1]">there is no pending received req</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
