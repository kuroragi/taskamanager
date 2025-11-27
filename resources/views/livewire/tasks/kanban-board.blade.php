<div>
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

    {{-- Kanban Columns --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($columns as $status)
            <div class="bg-gray-100 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-4 capitalize">
                    {{ ucfirst($status) }}
                    <span class="text-sm text-gray-500">({{ $this->getTasksByStatus($status)->count() }})</span>
                </h3>
                
                <div class="space-y-3">
                    @forelse($this->getTasksByStatus($status) as $task)
                        <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">{{ $task->title }}</h4>
                            
                            <div class="space-y-1 text-sm text-gray-600">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-indigo-600">Prioritas:</span>
                                    <span class="font-bold">{{ number_format($task->priority_score, 2) }}</span>
                                </div>
                                
                                @if($task->deadline)
                                    <div class="flex items-center justify-between">
                                        <span>Deadline:</span>
                                        <span class="text-xs {{ $task->deadline->isPast() ? 'text-red-600 font-semibold' : ($task->deadline->diffInDays() <= 3 ? 'text-orange-600' : '') }}">
                                            {{ $task->deadline->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif
                                
                                @if($task->energy)
                                    <div class="flex items-center justify-between">
                                        <span>Energi:</span>
                                        <span class="text-xs">{{ $task->energy }}/10</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Status Change Dropdown --}}
                            <div class="mt-3">
                                <select 
                                    wire:change="move({{ $task->id }}, $event.target.value)"
                                    class="w-full text-xs px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                >
                                    <option value="">Pindah ke...</option>
                                    @foreach($columns as $col)
                                        @if($col !== $status)
                                            <option value="{{ $col }}">{{ ucfirst($col) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 text-sm py-8">
                            Tidak ada task
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- Event Listener for Notifications --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('task-moved', (event) => {
                alert('Task berhasil dipindah!');
            });
        });
    </script>
</div>
