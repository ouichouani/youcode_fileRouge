@extends('components.layout')

@section('title')
    CREATE REPORT
@endsection

@section('content')
    <section class="mx-auto w-full max-w-4xl px-4 py-6">
        <div class="mb-6 rounded-2xl border border-white/10 bg-[#151b23] px-6 py-5 shadow-lg">
            <h2 class="text-xl font-bold tracking-wide text-white">Create a report</h2>
            <p class="mt-2 text-sm text-[#9198a1]">
                Explain what is wrong with this post and choose the reason that matches best.
            </p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST"
            class="rounded-2xl border border-white/10 bg-[#151b23] p-6 shadow-lg">
            @csrf

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    A few fields still need attention before this report can be sent.
                </div>
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="type" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Report type</span>
                        <select name="type" id="type"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white focus:bg-transparent focus:outline-blue-500 focus:outline-2">
                            <option class="bg-[#151b23] text-white" value="spam">spam</option>
                            <option class="bg-[#151b23] text-white" value="harassment">harassment</option>
                            <option class="bg-[#151b23] text-white" value="hate_speech">hate_speech</option>
                            <option class="bg-[#151b23] text-white" value="violence">violence</option>
                            <option class="bg-[#151b23] text-white" value="nudity">nudity</option>
                            <option class="bg-[#151b23] text-white" value="misinformation">misinformation</option>
                            <option class="bg-[#151b23] text-white" value="copyright">copyright</option>
                            <option class="bg-[#151b23] text-white" value="scam">scam</option>
                            <option class="bg-[#151b23] text-white" value="other">other</option>
                        </select>
                    </label>
                    @error('type')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="flex flex-col gap-2">
                        <span class="text-sm font-medium text-white">Description</span>
                        <textarea id="description" name="description" rows="7" placeholder="describe the problem"
                            class="p-2 px-3 bg-[#0d1117] border border-solid border-white/20 rounded-lg text-white placeholder:text-[#9198a1] focus:bg-transparent focus:outline-blue-500 focus:outline-2">{{ old('description') }}</textarea>
                    </label>
                    @error('description')
                        <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="post_id" value="{{ $post_id }}">

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('posts.index') }}"
                    class="rounded-lg border border-white/10 bg-[#151b23] px-6 py-2 text-sm font-medium text-[#9198a1] transition hover:border-white/20 hover:text-white">
                    back
                </a>
                <button
                    class="rounded-lg border border-red-400/30 bg-red-500/10 px-6 py-2 text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                    report
                </button>
            </div>
        </form>
    </section>

@endsection
