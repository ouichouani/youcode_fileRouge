@extends('components.layout')

@section('title')
    EDIT PROFILE
@endsection


@section('content')
    <section class="mx-auto w-full max-w-4xl px-4 py-6">
        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
            <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ asset($user->image?->path ? 'storage/' . $user->image->path : 'images/blank-profile.webp') }}"
                        alt="{{ $user->name }}"
                        class="h-20 w-20 rounded-full border border-white/20 bg-[#0d1117] object-cover">
                    <div>
                        <h2 class="text-xl font-bold tracking-wide text-white">Edit profile</h2>
                        <p class="mt-2 text-sm text-[#9198a1]">
                            Update your account details with the same clean workflow used across the app.
                        </p>
                    </div>
                </div>

                <a href="{{ route('users.show', $user->id) }}"
                    class="rounded-lg border border-white/10 bg-[#0d1117] px-5 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                    back to profile
                </a>
            </div>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
            class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    A few fields still need attention before this profile can be updated.
                </div>
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Name</span>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                    </label>
                    @error('name')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="bio" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Bio</span>
                        <textarea id="bio" name="bio" rows="5"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('bio', $user->bio) }}</textarea>
                    </label>
                    @error('bio')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="password" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Password</span>
                        <input id="password" type="password" name="password"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                    </label>
                    @error('password')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Confirm password</span>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label for="images" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Profile image</span>
                        <input id="images" type="file" name="image" accept="image/*"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-sm text-white file:mr-4 file:rounded-md file:border-0 file:bg-[#151b23] file:px-3 file:py-2 file:text-sm file:text-white">
                    </label>
                    <p class="mt-2 text-sm text-[#9198a1]">Upload a new image if you want to replace your current profile picture.</p>
                    @error('image')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                    @error('images')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('users.show', $user->id) }}"
                    class="rounded-lg border border-white/10 bg-[#151b23] px-6 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                    back
                </a>
                <button type="submit"
                    class="rounded-lg border border-white/20 bg-[#0d1117] px-6 py-2 text-sm font-medium text-white transition hover:bg-green-500/20">
                    update
                </button>
            </div>
        </form>
    </section>

@endsection
