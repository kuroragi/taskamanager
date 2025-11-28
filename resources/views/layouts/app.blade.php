<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex space-x-8">
                    <a href="{{ route('kanban') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('kanban') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                        Kanban
                    </a>
                    <a href="{{ route('priority') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('priority') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                        Prioritas
                    </a>
                    <a href="{{ route('tasks') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('tasks') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                        Tugas
                    </a>
                    <a href="{{ route('stats') }}"
                        class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('stats') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                        Statistik
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        @yield('content')
    </main>

    @livewireScripts
</body>

</html>
