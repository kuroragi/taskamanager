<div>
    {{-- Mode Switcher --}}
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex space-x-2">
            <button 
                wire:click="$set('mode', 'priority')"
                class="px-4 py-2 rounded-md {{ $mode === 'priority' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
            >
                Prioritas
            </button>
            <button 
                wire:click="$set('mode', 'deadline')"
                class="px-4 py-2 rounded-md {{ $mode === 'deadline' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
            >
                Nearest Deadline
            </button>
            <button 
                wire:click="$set('mode', 'easiest')"
                class="px-4 py-2 rounded-md {{ $mode === 'easiest' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
            >
                Easiest Task
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Cari task..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Deadline</label>
                <select 
                    wire:model.live="deadlineFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Semua</option>
                    <option value="overdue">Terlambat</option>
                    <option value="soon">Segera (≤3 hari)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Energi Minimal</label>
                <input 
                    type="number" 
                    wire:model.live="energyMin" 
                    min="1" 
                    max="10"
                    placeholder="1-10"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>
        </div>
    </div>

    {{-- Task List Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obligation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    @if($mode === 'priority' || $mode === 'deadline')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Energy</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    if ($mode === 'deadline') {
                        $tasks = $this->nearestDeadline();
                    } elseif ($mode === 'easiest') {
                        $tasks = $this->easiest();
                    } else {
                        $tasks = $this->list();
                    }
                @endphp

                @forelse($tasks as $task)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="text-xs text-gray-500">{{ Str::limit($task->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-indigo-600">{{ number_format($task->priority_score, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task->difficulty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task->obligation }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task->desire }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($task->deadline)
                                <span class="text-sm {{ $task->deadline->isPast() ? 'text-red-600 font-semibold' : ($task->deadline->diffInDays() <= 3 ? 'text-orange-600' : 'text-gray-900') }}">
                                    {{ $task->deadline->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $task->status === 'done' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $task->status === 'doing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $task->status === 'todo' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $task->status === 'hold' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        @if($mode === 'priority' || $mode === 'deadline')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $task->energy ?? '-' }}
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="{{ route('tasks') }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            Belum ada task sesuai filter
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
