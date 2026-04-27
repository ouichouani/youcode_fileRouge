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

            <section class="h-[50%] w-[50%] pl-[10vw] lg:flex hidden flex-col gap-8 border-r border-solid border-white/30">
                <h1 class="text-[3.3em] font-bold">WELCOM, WARRIOR</h1>
                <p class="text-[#9198a1] pl-[10px] w-[90%] ">Hi there! Welcome to Habit Trackr.<br> We’re so excited to
                    help you start crushing your goals! Whether you're here to build new habits or just keep your daily
                    tasks on track, you've got a whole community behind you.
                    <br> <br>Time to lock in and get to work!
                </p>
            </section>

            <section class="lg:h-[70%] lg:w-[50%] w-full h-full flex justify-center items-center ">
                <div class="w-[60%] h-fit">
                    <h1 class="text-[2em] font-bold mb-10">REGISTER</h1>

                    @if (session('error'))
                        <div style="color: red; margin-bottom: 12px;">
                            {{ session('error') }}
                        </div>
                    @endif



                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col gap-7 max-h-[60vh] px-3 overflow-auto [&::-webkit-scrollbar]:w-[1px] [&::-webkit-scrollbar-thumb]:bg-blue-500">
                        @csrf

                        <div>
                            <label for="Name" class="flex flex-col gap-2">
                                <p>Name</p>
                                <input id="Name" type="email" name="name" value="{{ old('name') }}" required
                                    class="p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                            </label>

                            @error('email')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

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

                        <div class="flex flex-col gap-2">
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                            @error('password')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="flex flex-col gap-2">
                            <label for="bio">bio</label>
                            <textarea id="bio" name="bio" rows="4"
                                class="p-1 px-2 bg-[#151b23] border border-solid border-white/20 rounded-lg focus:bg-transparent focus:outline-blue-500 focus:outline-2 ">
                                {{ old('bio') }}
                            </textarea>

                            @error('bio')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        {{-- <div>
                            <label for="bio">image</label>
                            <input type="file" name="image">
                            @error('image')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <button type="submit"
                            class="p-1 w-[50%] m-auto bg-[#151b23] border border-solid border-white/20 rounded-lg hover:bg-green-500/20 ">
                            Let's get to work
                        </button>

                    </form>

                    <p class="text-center mt-5 text-[#9198a1]">
                        already have an account ?
                        <a href="{{ route('login') }}" class="hover:text-white">login</a>
                    </p>
                </div>
            </section>







        </div>
    </main>

</body>

</html>
