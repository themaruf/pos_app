<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-vh-100">
        @include('layouts.navigation')

        <div class="d-flex">
            @include('components.sidebar')

            <div class="flex-grow-1">
                <main class="p-4">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
