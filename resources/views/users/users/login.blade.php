<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOGIN</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body>


    <main class="w-full h-screen bg-[#0d1117] text-white">

        <div class="flex h-full items-center">

            <section class="h-[50%] w-[50%] pl-[10vw] flex flex-col gap-8 border-r border-solid border-white/30">
                <h1 class="text-[3.3em] font-bold">BACK IN ACTION</h1>
                <p class="text-[#9198a1] pl-[10px] w-[90%] ">Great to see you again! Your habits define your future, and
                    you’ve just made the best choice of the day by showing up for yourself. Ready to tick off some goals
                    and crush your streaks? Let’s get to work!</p>
            </section>

            <section class="h-[70%] w-[50%] flex justify-center items-center ">
                <div class="w-[60%] h-fit">
                <h1 class="text-[2em] font-bold mb-10">LOGIN</h1>

                @if (session('error'))
                    <div style="color: red; margin-bottom: 12px;">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-7">
                    @csrf

                    <div>
                        <label for="email" class="flex flex-col gap-2">
                            <p>Email</p>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                        </label>

                        @error('email')
                            <div style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required
                            class="p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                        @error('password')
                            <div style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="p-1 w-[50%] m-auto bg-[#151b23] border border-solid border-white/20 rounded-lg hover:bg-green-500/20 " >Let's go</button>
                </form>

                <p class="text-center mt-5 text-[#9198a1]">
                    Don't have an account ?
                    <a href="{{ route('register') }}" class="hover:text-white">Register</a>
                </p>
                </div>
            </section>
            
        </div>


    </main>

</body>

</html>
