@extends('components.layout')


@section('title')
    SHOW USER
@endsection


@section('content')
    <section class="mx-auto w-full max-w-6xl px-4 py-6">

        <div class="w-full flex items-center justify-center">
            <div class="flex flex-col items-end">
                <div class="w-[60vw] mb-6 rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                        <div class="flex flex-1 items-start gap-5">
                            <div class="flex justify-between items-center w-full flex-wrap ">
                                <div class="flex gap-20 items-center min-w-[300px]  ">
                                    <div class="flex gap-4 items-center">

                                        <img src="{{ asset($user?->image?->path ? 'storage/' . $user?->image?->path : 'images/blank-profile.webp') }}"
                                            alt="{{ $user->name }}"
                                            class="h-40 w-40 rounded-full border border-white/20 bg-[#0d1117] object-cover">

                                        <div>
                                            <h2
                                                class="text-2xl font-bold {{ $user->role == 'Admin' ? 'text-yellow-200' : ($user->role == 'Moderator' ? 'text-red-500' : 'text-white') }}">
                                                {{ $user->name }}</h2>
                                            <p class="text-md text-white">{{ $user->email }}</p>
                                            <p>rank :
                                                {{ $user->score == null || $user->score == 0 ? 'nooby' : ($user->score < 100 ? 'bot hhh' : ($user->score > 100 && $user->score < 200 ? 'hmm good' : 'the GOAT')) }}
                                            </p>
                                            <p class="text-md text-white ">{{ $user->bio }}</p>
                                        </div>

                                    </div>
                                </div>

                                <div class="flex flex-col min-w-[200px]">
                                    <div>
                                        <div class="flex gap-3 sm:grid-cols-2">
                                            <div class="px-4 py-3">
                                                <p class="mt-2 text-lg text-center text-white">{{ count($sentRequests) }}
                                                </p>
                                                <p class="text-xs text-[#9198a1]">Following</p>
                                            </div>
                                            <div class="px-4 py-3">
                                                <p class="mt-2 text-lg text-center text-white">
                                                    {{ count($receivedRequests) }}
                                                </p>
                                                <p class="text-xs text-[#9198a1]">Followers</p>
                                            </div>
                                            <div class="px-4 py-3">
                                                <p class="mt-2 text-lg text-center text-white">{{ count($posts) }}</p>
                                                <p class="text-xs text-[#9198a1]">Posts</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 items-center">
                    {{-- update button --}}
                    @can('update', $user)
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                            update
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                logout
                            </button>
                        </form>
                    @endcan

                    {{-- ban button --}}
                    @can('manage_app', App\Models\User::class)
                        @if (auth()->user()->id != $user->id && auth()->user() != 'Client')
                            @if (auth()->user()->role == 'Admin' && $user->role != 'Admin')
                                <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                                    @csrf
                                    <button
                                        class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                        {{ $user->is_banned || $user->is_banned_by_moderator ? 'unban a' : 'ban' }}
                                    </button>
                                </form>
                            @elseif (auth()->user()->role == 'Moderator' && !$user->is_banned && $user->role == 'Client')
                                <form action="{{ route('moderator.users.ban', $user) }}" method="POST">
                                    @csrf
                                    <button
                                        class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                        {{ $user->is_banned_by_moderator ? 'unban' : 'temp ban' }}
                                    </button>
                                </form>
                            @elseif ($user->is_banned)
                                <button disabled
                                    class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                    baned by admin
                                </button>
                            @endif
                        @endif
                    @endcan

                    {{-- accept or reject button --}}
                    @if (isset($pendingRequest) && $pendingRequest?->status == 'pending')
                        <section>
                            @if ($pendingRequest?->sender_id == Auth::id())
                                <form action="{{ route('requests.destroy', $pendingRequest->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                        cansel
                                    </button>
                                </form>
                            @endif

                            @if ($pendingRequest?->receiver_id == Auth::id())
                                <div class="flex flex-wrap gap-3">
                                    <form action="{{ route('requests.reject', $pendingRequest->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="rounded-lg border border-red-400/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                                            reject
                                        </button>
                                    </form>
                                    <form action="{{ route('requests.accept', $pendingRequest->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                            accept
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </section>
                    @endif

                    {{-- add friend button --}}
                    @if (!$isFriend)
                        @if ($user->id != auth()->user()->id && !isset($pendingRequest))
                            <form action="{{ route('requests.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                                <button
                                    class="rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                    add friend
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        
        <section class="rounded p-6">
            <div class="mb-6 flex items-center justify-between gap-4">
                <a title='add post' href='{{ route('posts.create') }}' 
                    class="rounded-full border border-white/20 bg-[#0d1117] w-10 h-10 text-sm font-medium text-white transition hover:border-white/50 cursor-pointer flex items-center justify-center">
                    <span>+</span>
            </a>
            </div>

            <div class="flex flex-col gap-6">
                @forelse ($posts as $post)
                    <article class="rounded-2xl border border-white/10 shadow-lg">
                        <div class="flex flex-col gap-5 bg">
                            <div class="flex items-start justify-between gap-4">
                                <div
                                    class="bg-[#151b23] w-full px-6 py-2 rounded-t-2xl flex justify-between {{ $post->visibility == 'private' ? 'bg-[#25171c]' : ($post->visibility == 'friends' ? 'bg-[#17251c]' : 'bg-[#151b23]') }}">
                                    <a href="{{ route('users.show', $post->user->id) }}"
                                        class="flex items-center gap-4 w-fit ">
                                        <img class="h-12 w-12 rounded-full border border-white/20 bg-[#0d1117] object-cover"
                                            src="{{ asset($post->user->image?->path ? 'storage/' . $post->user->image?->path : 'images/blank-profile.webp') }}"
                                            alt="{{ $post->user->name }}">
                                        <div>
                                            <h3 class="text-md font-semibold text-white w-fit">{{ $post->user->name }}</h3>
                                            <p class="text-sm text-[#9198a1]">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>

                                    <div class="flex items-center gap-3">
                                        @can('store', App\Models\Report::class)
                                            @if (auth()->user()->id != $post->user_id)
                                                <a href="{{ route('reports.create', ['post' => $post->id]) }}">
                                                    <img class="w-[25px] cursor-pointer" src="{{ asset('svg/report.svg') }}"
                                                        alt="report" title="report">
                                                </a>
                                            @endif
                                        @endcan

                                        @can('hide', $post)
                                            <form action="{{ route('posts.hide', $post) }}" method="POST"
                                                class="flex items-center">
                                                @csrf
                                                <button>
                                                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                                        class="text-[#848b93] cursor-pointer hover:text-red-400"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0" />
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <g id="SVGRepo_iconCarrier">
                                                            <g id="Edit / Hide">
                                                                <path id="Vector"
                                                                    d="M3.99989 4L19.9999 20M16.4999 16.7559C15.1473 17.4845 13.6185 17.9999 11.9999 17.9999C8.46924 17.9999 5.36624 15.5478 3.5868 13.7788C3.1171 13.3119 2.88229 13.0784 2.7328 12.6201C2.62619 12.2933 2.62616 11.7066 2.7328 11.3797C2.88233 10.9215 3.11763 10.6875 3.58827 10.2197C4.48515 9.32821 5.71801 8.26359 7.17219 7.42676M19.4999 14.6335C19.8329 14.3405 20.138 14.0523 20.4117 13.7803L20.4146 13.7772C20.8832 13.3114 21.1182 13.0779 21.2674 12.6206C21.374 12.2938 21.3738 11.7068 21.2672 11.38C21.1178 10.9219 20.8827 10.6877 20.4133 10.2211C18.6338 8.45208 15.5305 6 11.9999 6C11.6624 6 11.3288 6.02241 10.9999 6.06448M13.3228 13.5C12.9702 13.8112 12.5071 14 11.9999 14C10.8953 14 9.99989 13.1046 9.99989 12C9.99989 11.4605 10.2135 10.9711 10.5608 10.6113"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan

                                        @if ($user->id === $post->user_id)
                                            <a href="{{ route('posts.edit', $post->id) }}"
                                                class="rounded-lg border border-white/20 bg-[#0d1117] px-4 py-2 text-sm font-medium text-white transition hover:border-white/50">
                                                update
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="px-6">
                                <div class="mb-4 w-[80%]">
                                    <p class="whitespace-pre-line text-sm leading-7 text-white">{{ $post->content }}</p>
                                </div>

                                @if (count($post->images))
                                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                        @foreach ($post->images as $img)
                                            <img class="h-56 w-full rounded-xl border border-white/10 bg-[#0d1117] object-cover"
                                                src="{{ asset('storage/' . $img->path) }}" alt="post image">
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-3 px-5 py-4">
                                <form action="{{ route('likes.save') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">

                                    <button class="flex gap-2 items-center cursor-pointer">
                                        <img class="w-[22px]"
                                            src="{{ asset($post->likes->contains('user_id', auth()->id()) ? 'svg/unlike.svg' : 'svg/like.svg') }}"
                                            alt="">
                                        <p class="text-sm font-semibold text-white">{{ count($post->likes) }}</p>
                                    </button>
                                </form>

                                <button class="comments_button flex gap-2 items-center cursor-pointer">
                                    <img class="w-[25px]" src="{{ asset('svg/comments.svg') }}" alt="">
                                    <p class="text-sm font-semibold text-white">{{ count($post->comments) }}</p>
                                </button>

                                <div class="px-3 py-1 text-xs text-[#9198a1] ml-[-10px]">
                                    @if ($post->is_hidden)
                                        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                            class=" text-[#848b93] hover:text-red-400" xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <g id="SVGRepo_iconCarrier">
                                                <g id="Edit / Hide">
                                                    <path id="Vector"
                                                        d="M3.99989 4L19.9999 20M16.4999 16.7559C15.1473 17.4845 13.6185 17.9999 11.9999 17.9999C8.46924 17.9999 5.36624 15.5478 3.5868 13.7788C3.1171 13.3119 2.88229 13.0784 2.7328 12.6201C2.62619 12.2933 2.62616 11.7066 2.7328 11.3797C2.88233 10.9215 3.11763 10.6875 3.58827 10.2197C4.48515 9.32821 5.71801 8.26359 7.17219 7.42676M19.4999 14.6335C19.8329 14.3405 20.138 14.0523 20.4117 13.7803L20.4146 13.7772C20.8832 13.3114 21.1182 13.0779 21.2674 12.6206C21.374 12.2938 21.3738 11.7068 21.2672 11.38C21.1178 10.9219 20.8827 10.6877 20.4133 10.2211C18.6338 8.45208 15.5305 6 11.9999 6C11.6624 6 11.3288 6.02241 10.9999 6.06448M13.3228 13.5C12.9702 13.8112 12.5071 14 11.9999 14C10.8953 14 9.99989 13.1046 9.99989 12C9.99989 11.4605 10.2135 10.9711 10.5608 10.6113"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                            </g>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <div class="comments-container hidden px-6 mb-3 ">
                                @forelse ($post->comments as $comment)
                                    <div class="border border-solid border-white/10 rounded-2xl mb-3">
                                        <div class="bg-[#151b23] w-full mb-3 py-2 px-2 rounded-t-2xl flex justify-between">
                                            <a href="{{ route('users.show', $comment->user?->id) }}"
                                                class="flex items-center gap-4 w-fit">
                                                <img class="h-10 w-10 rounded-full border border-white/20 bg-[#0d1117] object-cover"
                                                    src="{{ asset($comment->user->image?->path ? 'storage/' . $comment->user->image?->path : 'images/blank-profile.webp') }}"
                                                    alt="{{ $comment->user->name }}">
                                                <div>
                                                    <h3 class="text-sm font-semibold text-white w-fit">
                                                        {{ $comment->user->name }}
                                                    </h3>
                                                    <p class="text-xs text-[#9198a1]">
                                                        {{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                            </a>

                                            @can('delete', $comment)
                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button>
                                                        <svg width="25px" height="25px" viewBox="0 0 24 24"
                                                            fill="none"
                                                            class="cursor-pointer transition hover:text-red-400"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <g id="SVGRepo_iconCarrier">
                                                                <path
                                                                    d="M6 5H18M9 5V5C10.5769 3.16026 13.4231 3.16026 15 5V5M9 20H15C16.1046 20 17 19.1046 17 18V9C17 8.44772 16.5523 8 16 8H8C7.44772 8 7 8.44772 7 9V18C7 19.1046 7.89543 20 9 20Z"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </g>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>

                                        <div class="px-6 py-4">
                                            <p class="w-[80%]">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[#9198a1] p-3 w-[90%]">no comments for the moment</p>
                                @endforelse

                                <form action="{{ route('comments.store') }}" method="POST"
                                    class="flex gap-1 items-center">
                                    @csrf
                                    <input type="text" name='content' placeholder="comment"
                                        class="w-full p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <button>
                                        <svg class="cursor-pointer transition hover:text-blue-400" width="25px"
                                            height="25px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M10.3009 13.6949L20.102 3.89742M10.5795 14.1355L12.8019 18.5804C13.339 19.6545 13.6075 20.1916 13.9458 20.3356C14.2394 20.4606 14.575 20.4379 14.8492 20.2747C15.1651 20.0866 15.3591 19.5183 15.7472 18.3818L19.9463 6.08434C20.2845 5.09409 20.4535 4.59896 20.3378 4.27142C20.2371 3.98648 20.013 3.76234 19.7281 3.66167C19.4005 3.54595 18.9054 3.71502 17.9151 4.05315L5.61763 8.2523C4.48114 8.64037 3.91289 8.83441 3.72478 9.15032C3.56153 9.42447 3.53891 9.76007 3.66389 10.0536C3.80791 10.3919 4.34498 10.6605 5.41912 11.1975L9.86397 13.42C10.041 13.5085 10.1295 13.5527 10.2061 13.6118C10.2742 13.6643 10.3352 13.7253 10.3876 13.7933C10.4468 13.87 10.491 13.9585 10.5795 14.1355Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div
                        class="rounded-xl border border-dashed border-white/15 bg-[#0d1117] px-4 py-5 text-sm text-[#9198a1]">
                        no posts yet
                    </div>
                @endforelse
            </div>
        </section>

    </section>
@endsection

@push('scripts')
    <script>
        let comments_button = document.querySelectorAll('.comments_button');

        comments_button.forEach(button => {
            button.addEventListener('click', (e) => {
                const wrapper = e.currentTarget.closest('article');
                const container = wrapper.querySelector('.comments-container');

                if (container.style.display === 'block') {
                    container.style.display = 'none';
                } else {
                    container.style.display = 'block';
                }
            })
        })
    </script>
@endpush
