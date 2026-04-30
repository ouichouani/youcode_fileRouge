<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body class="bg-[#0d1117]">
    @props([
    'title' => 'i think you lost : 404 ',
    'message' => 'Nothing interinsting here for the moment , what about go back to index page ? ',
    'action' => 'go back to dashboard',
    'href' => route('dashboard'),
])

<section class="mx-auto flex w-full max-w-3xl items-center justify-center py-10">
    <div class="w-full rounded-2xl border border-dashed border-white/15 bg-[#151b23] px-6 py-10 text-center shadow-lg sm:px-10">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-white/10 bg-[#0d1117]">
            <svg class="h-8 w-8 text-[#9198a1]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.172 9a3 3 0 1 1 5.656 2" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path
                    d="M8.4 3h7.2c2.52 0 3.78 0 4.743.49a4.5 4.5 0 0 1 1.967 1.967C22.8 6.42 22.8 7.68 22.8 10.2v3.6c0 2.52 0 3.78-.49 4.743a4.5 4.5 0 0 1-1.967 1.967c-.963.49-2.223.49-4.743.49H8.4c-2.52 0-3.78 0-4.743-.49a4.5 4.5 0 0 1-1.967-1.967c-.49-.963-.49-2.223-.49-4.743v-3.6c0-2.52 0-3.78.49-4.743A4.5 4.5 0 0 1 3.657 3.49C4.62 3 5.88 3 8.4 3Z"
                    stroke="currentColor" stroke-width="1.8" />
            </svg>
        </div>

        <h2 class="mt-5 text-2xl font-bold text-white">{{ $title }}</h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-[#9198a1]">{{ $message }}</p>

        @if ($href && $action)
            <div class="mt-6">
                <a href="{{ $href }}"
                    class="inline-flex items-center rounded-lg border border-white/20 bg-[#0d1117] px-5 py-2 text-sm font-medium text-white transition hover:border-white/50">
                    {{ $action }}
                </a>
            </div>
        @endif
    </div>
</section>

</body>
</html>