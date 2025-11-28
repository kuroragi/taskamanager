@extends('layouts.app')

@section('title', 'Kanban - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kanban Board</h1>
            <p class="text-gray-600">Kelola task dengan sistem prioritas otomatis</p>
        </div>

        @livewire('tasks.kanban-board')
    </div>
@endsection
