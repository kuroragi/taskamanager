<div>
    {{-- Filter Bar --}}
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live="search" placeholder="Cari task..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Deadline</label>
                <select wire:model.live="deadlineFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    <option value="overdue">Terlambat</option>
                    <option value="soon">Segera (≤3 hari)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Energi Minimal</label>
                <input type="number" wire:model.live="energyMin" min="1" max="10" placeholder="1-10"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    {{-- Kanban Columns --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($columns as $status)
            <div class="bg-gray-100 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-4 capitalize">
                    {{ ucfirst($status) }}
                    <span class="text-sm text-gray-500">({{ $this->getTasksByStatus($status)->count() }})</span>
                </h3>

                <div class="space-y-3 kanban-column" data-status="{{ $status }}">
                    @forelse($this->getTasksByStatus($status) as $task)
                        <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-200 cursor-move task-item" 
                             data-task-id="{{ $task->id }}" 
                             data-current-status="{{ $task->status }}">
                            <h4 class="font-medium text-gray-900 mb-2">{{ $task->title }}</h4>

                            <div class="space-y-1 text-sm text-gray-600">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-indigo-600">Prioritas:</span>
                                    <span class="font-bold">{{ number_format($task->priority_score, 2) }}</span>
                                </div>

                                @if ($task->deadline)
                                    <div class="flex items-center justify-between">
                                        <span>Deadline:</span>
                                        <span
                                            class="text-xs {{ $task->deadline->isPast() ? 'text-red-600 font-semibold' : ($task->deadline->diffInDays() <= 3 ? 'text-orange-600' : '') }}">
                                            {{ $task->deadline->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif

                                @if ($task->energy)
                                    <div class="flex items-center justify-between">
                                        <span>Energi:</span>
                                        <span class="text-xs">{{ $task->energy }}/10</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Status Change Dropdown --}}
                            <div class="mt-3">
                                <select wire:change="move({{ $task->id }}, $event.target.value)"
                                    class="w-full text-xs px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <option value="">Pindah ke...</option>
                                    @foreach ($columns as $col)
                                        @if ($col !== $status)
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

    {{-- Drag and Drop JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking for Sortable...', typeof window.Sortable);
            
            if (typeof window.Sortable === 'undefined') {
                console.error('SortableJS not found! Make sure it\'s properly loaded.');
                return;
            }

            initializeDragAndDrop();
        });

        document.addEventListener('livewire:navigated', function() {
            initializeDragAndDrop();
        });

        function initializeDragAndDrop() {
            console.log('Initializing drag and drop...');
            
            // Clear any existing sortable instances
            document.querySelectorAll('.kanban-column').forEach(column => {
                if (column.sortable) {
                    column.sortable.destroy();
                }
            });

            // Initialize new sortable instances
            document.querySelectorAll('.kanban-column').forEach(column => {
                console.log('Setting up sortable for column:', column.dataset.status);
                
                const sortable = new window.Sortable(column, {
                    group: 'kanban-tasks',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    chosenClass: 'ring-2 ring-indigo-500',
                    dragClass: 'rotate-2',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onStart: function(evt) {
                        console.log('Drag started');
                    },
                    onEnd: function(evt) {
                        const taskId = evt.item.dataset.taskId;
                        const newStatus = evt.to.dataset.status;
                        const oldStatus = evt.from.dataset.status;
                        
                        console.log('Drag ended:', { taskId, oldStatus, newStatus });
                        
                        if (newStatus !== oldStatus && taskId) {
                            console.log('Calling Livewire move method...');
                            @this.call('move', parseInt(taskId), newStatus)
                                .then(() => {
                                    console.log('Move successful');
                                })
                                .catch(error => {
                                    console.error('Move failed:', error);
                                });
                        }
                    }
                });
                
                // Store reference for cleanup
                column.sortable = sortable;
            });
        }

        // Livewire event listeners
        document.addEventListener('livewire:init', () => {
            Livewire.on('task-moved', (event) => {
                console.log('Task moved successfully via Livewire');
            });
        });
    </script>
</div>
