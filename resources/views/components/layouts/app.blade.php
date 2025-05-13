<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapGallery</title>

    <!-- Tailwind CSS (via CDN or your own build) -->
    <script src="https://cdn.tailwindcss.com"></script>

   

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">SnapGallery 📸</a>
            <div class="flex space-x-4">
                <!-- Add nav items here if needed -->
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto p-6">
    {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow p-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} SnapGallery. Built with Laravel + Livewire + AlpineJS.
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts
<script>



window.addEventListener('reload-page', event => {
    location.reload();  // This reloads the entire page
});

</script>
    
</body>
</html>
