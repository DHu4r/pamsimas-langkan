<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>
<body class="bg-gray-200">
    <div class="flex items-center justify-center min-h-screen">
        <div class="min-h-96 w-96 bg-white rounded-2xl shadow-2xl drop-shadow-black">
            <div class="bg-slate-700 rounded-t-2xl pt-5 pb-2.5">
                <img src="img/logo.png" alt="Pamsimas" class="w-36 mx-auto">
                <h3 class="text-center text-2xl text-white font-bold ">Dusun Langkan</h3>
            </div>
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="block mx-10 mt-6">
                    <label for="no_hp" class="font-semibold text-gray-800">No. Handpone</label>
                    <input type="text" name="no_hp" class="min-w-full text-sm text-gray-600 border-1 border-slate-400 py-2 px-1.5 my-1.5 rounded-md focus:outline-amber-300" placeholder="Masukan nomor hp anda" required>
                    <label for="password" class="font-semibold text-gray-800">Password</label>
                    <input type="password" name="password" class="min-w-full text-sm text-gray-600 border-1 border-slate-400 py-2 px-1.5 my-1.5 rounded-md focus:outline-amber-300" placeholder="Masukan password anda" required>
                    @if ($errors->has('login'))
                        <div class="flex text-red-800 justify-center my-3 text-base rounded-lg bg-red-300 outline-1 outline-red-600">{{ $errors->first('login') }}</div>
                    @endif
                    <div class="flex justify-center">
                        <button class="min-w-0 px-8 py-1.5 font-semibold mt-5 rounded-xl bg-amber-300 cursor-pointer hover:bg-amber-700 hover:text-white shadow mb-4">Masuk</button>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</body>
</html>