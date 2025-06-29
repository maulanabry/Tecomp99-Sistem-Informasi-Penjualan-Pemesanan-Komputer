<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.24/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    <title>Login - Tecomp99</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Logo Section -->
        <div class="mb-8 text-center">
            <a href="/" class="inline-block transition-transform duration-300 hover:scale-105">
                <img class="h-16 w-auto mx-auto" src="/images/logo-tecomp99.svg" alt="Tecomp99 Logo">
            </a>
        </div>

        <!-- Card Container -->
        <div class="w-full max-w-md">
            <!-- Login Form Card -->
            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Selamat Datang!
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Silakan login dengan akun yang sesuai
                        </p>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <span class="font-medium text-red-700 dark:text-red-400">Terjadi kesalahan!</span>
                            </div>
                            <ul class="ml-6 list-disc text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form class="space-y-6" action="{{ route('auth.login') }}" method="POST">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" 
                                    class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 
                                        bg-gray-50 dark:bg-gray-700 
                                        text-gray-900 dark:text-white 
                                        focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-600 
                                        transition-colors duration-200"
                                    placeholder="nama@email.com" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="email">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password" id="password" 
                                    class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 
                                        bg-gray-50 dark:bg-gray-700 
                                        text-gray-900 dark:text-white 
                                        focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-600 
                                        transition-colors duration-200"
                                    placeholder="Masukkan Password Anda" 
                                    required>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" name="remember" id="remember" 
                                    class="h-4 w-4 rounded border-gray-300 text-primary-600 
                                        focus:ring-primary-500 dark:focus:ring-primary-600 
                                        dark:border-gray-600 dark:bg-gray-700 
                                        transition-colors duration-200">
                                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 
                                    border border-transparent text-sm font-medium rounded-lg 
                                    text-white bg-primary-600 hover:bg-primary-700 
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 
                                    focus:ring-primary-500 dark:focus:ring-offset-gray-800 
                                    transition-all duration-200 
                                    transform hover:scale-[1.02]">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-sign-in-alt text-primary-300 group-hover:text-primary-200 transition-colors duration-200"></i>
                                </span>
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                &copy; {{ date('Y') }} Tecomp99. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
