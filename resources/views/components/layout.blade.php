<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>@yield('title', 'habits grow')</title>
</head>

<body>


    
    <div class="bg-red-100 flex w-full h-screen">
        @include('components.sidebar')
        
        <section class="w-full bg-green-700 overflow-y-auto">
            @include('components.header')
            <main>
                @yield('content')
            </main>
            @include('components.footer')
        </section>
    </div>


</body>

</html>
