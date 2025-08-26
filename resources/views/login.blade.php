<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('sanoh-favicon.png') }}?v=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('sanoh-favicon.png') }}?v=1">
    
    <title>Login - Pull & Store FG System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sanoh-blue {
            background-color: #0A2856 !important;
        }
        .sanoh-blue-hover:hover {
            background-color: #0A2856 !important;
            opacity: 0.9;
        }
        .sanoh-blue-focus:focus {
            border-color: #0A2856 !important;
            box-shadow: 0 0 0 2px rgba(10, 40, 86, 0.1) !important;
        }
        .sanoh-blue-text {
            color: #0A2856 !important;
        }
    </style>
</head>
<body class="bg-cover bg-center bg-no-repeat min-h-screen" style="background-image: url('/sanoh-bg.png');">
    <div class="flex min-h-screen">
        <!-- Login Card - Responsive layout -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 lg:p-12">
            <div class="bg-white rounded-2xl p-6 sm:p-8 lg:p-12 w-full max-w-md lg:max-w-lg shadow-2xl border border-gray-100">
                <div class="text-center mb-6 sm:mb-8">
                    <img src="/sanoh-logo.png" alt="SANOH" class="h-6 sm:h-8 mx-auto mb-3 sm:mb-4">
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">Welcome Back</h1>
                    <p class="text-xs sm:text-sm text-gray-600">Sign in to your account</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('login.post') }}" class="space-y-3 sm:space-y-4">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" id="username" name="username" required 
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none sanoh-blue-focus transition-colors text-sm bg-white">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required 
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none sanoh-blue-focus transition-colors text-sm pr-10 bg-white">
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors" onclick="togglePassword()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full sanoh-blue text-white py-2.5 rounded-lg font-medium sanoh-blue-hover transition-all duration-200 text-sm mt-4 sm:mt-6">
                        Sign In
                    </button>
                </form>
                
                <div class="text-center mt-4 sm:mt-6 text-xs text-gray-500 leading-relaxed">
                    By signing in, you agree to our 
                    <a href="#" class="sanoh-blue-text hover:underline font-medium">Terms of Service</a> and 
                    <a href="#" class="sanoh-blue-text hover:underline font-medium">Privacy Policy</a>
                </div>
            </div>
        </div>
        
        <!-- Right side - Hidden on small screens, visible on large screens -->
        <div class="hidden lg:block lg:w-1/2"></div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleBtn = document.querySelector('button[onclick="togglePassword()"]');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                `;
            } else {
                passwordField.type = 'password';
                toggleBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                `;
            }
        }
    </script>
</body>
</html>
