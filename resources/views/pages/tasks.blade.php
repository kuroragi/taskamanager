@extends('layouts.app')

@section('title', 'Manajemen Tugas - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Tugas</h1>
            <p class="text-gray-600">Buat, edit, dan kelola task Anda</p>
        </div>

        @livewire('tasks.task-manager')
    </div>
@endsection
