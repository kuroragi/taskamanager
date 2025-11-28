@extends('layouts.app')

@section('title', 'Daftar Prioritas - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Prioritas</h1>
            <p class="text-gray-600">Smart view dengan berbagai mode sorting</p>
        </div>

        @livewire('tasks.priority-list')
    </div>
@endsection
