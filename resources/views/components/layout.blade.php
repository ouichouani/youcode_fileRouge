<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>@yield('title', 'habits grow')</title>
</head>

<body class="bg-[#151b23]">
    
    <div class="flex w-full h-screen">
        @include('components.sidebar')
        
        <section class="w-full overflow-y-auto bg-[#0d1117] text-white [&::-webkit-scrollbar]:w-[1px] [&::-webkit-scrollbar-thumb]:bg-blue-500">
            @include('components.header')
            <main class="text-sm p-[1em]">
                @yield('content')
            </main>
        </section>
    </div>

    @stack('script')
    @stack('scripts')


</body>

</html>
