@extends('components.layout')

@section('title')
    REQUESTS
@endsection

@section('content')
    <section class="mx-auto w-full max-w-5xl ">

        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg hidden sm:block">
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
                <article class="rounded-2xl border border-white/10 bg-[#151b23] p-2 md:p-4 shadow-lg">
                    <div class="flex justify-between items-center md:gap-5 lg:flex-row lg:items-center lg:justify-between">

                        <div class="flex items-start gap-4">
                            <img src="{{ asset($f->sender->image?->path ? 'storage/' . $f->sender->image->path : 'images/blank-profile.webp') }}"
                                alt="{{ $f->sender->name }}"
                                class="h-10 w-10 md:w-16 md:h-16 rounded-full border border-white/20 bg-[#0d1117] object-cover">

                            <div>
                                <a href="{{ route('users.show', $f->sender->id) }}">
                                    <p class="md:text-lg text-md font-semibold text-white">{{ $f->sender->name }}</p>
                                    <p class="mt-1 md:text-sm text-xs  text-[#9198a1]">{{ $f->sender->email }}</p>
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if ($f->status == 'pending')
                                <form action="{{ route('requests.accept', $f->id) }}" method="POST">
                                    @csrf
                                    <button title="accept"
                                        class="rounded-full border w-10 h-10 flex items-center justify-center cursor-pointer border-white/20 bg-[#0d1117] text-sm font-medium text-white transition hover:border-white/50">
                                        <svg width="12px" height="12px" viewBox="0 0 32 32"
                                            xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                                            <g id="SVGRepo_iconCarrier">
                                                <defs>
                                                    <style>
                                                        .cls-1 {
                                                            fill: #ffffff;
                                                        }
                                                    </style>
                                                </defs>
                                                <g id="check">
                                                    <path class="cls-1"
                                                        d="M12.16,28a3,3,0,0,1-2.35-1.13L3.22,18.62a1,1,0,0,1,1.56-1.24l6.59,8.24A1,1,0,0,0,13,25.56L27.17,4.44a1,1,0,1,1,1.66,1.12L14.67,26.67A3,3,0,0,1,12.29,28Z" />
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                </form>

                                <form action="{{ route('requests.reject', $f->id) }}" method="POST">
                                    @csrf
                                    <button title="refuse"
                                        class="rounded-full border w-10 h-10 flex items-center justify-center cursor-pointer border-red-400/30 bg-red-500/10 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                        x
                                    </button>
                                </form>
                            @endif

                            @if ($f->status == 'rejected')
                                <form action="{{ route('requests.destroy', $f->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class=" rounded-full border  w-10 h-10 flex items-center justify-center cursor-pointer  border-white/10 bg-[#151b23] text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-red-500">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" class=""
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M6 5H18M9 5V5C10.5769 3.16026 13.4231 3.16026 15 5V5M9 20H15C16.1046 20 17 19.1046 17 18V9C17 8.44772 16.5523 8 16 8H8C7.44772 8 7 8.44772 7 9V18C7 19.1046 7.89543 20 9 20Z"
                                                    stroke="CurrentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            @if ($f->status == 'accepted')
                                <div class="flex flex-wrap gap-3">
                                    <span
                                        class="rounded-full border px-5 py-2 text-sm {{ $f->status === 'pending' ? 'border-yellow-400/20 bg-yellow-500/10 text-yellow-200' : ($f->status === 'accepted' ? 'border-green-400/20 bg-green-500/10 text-green-200' : 'border-red-400/20 bg-red-500/10 text-red-200') }}">
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
