<div>
    {{-- Form Create/Edit --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            {{ $editing ? 'Edit Task' : 'Buat Task Baru' }}
        </h2>

        <form wire:submit="{{ $editing ? 'update' : 'create' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model="form.title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                    @error('form.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea 
                        wire:model="form.description"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    ></textarea>
                    @error('form.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Difficulty --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Difficulty (1-10) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        wire:model="form.difficulty"
                        min="1" 
                        max="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                    @error('form.difficulty') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Desire --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Desire (1-10) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        wire:model="form.desire"
                        min="1" 
                        max="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                    @error('form.desire') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Obligation --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Obligation (1-10) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        wire:model="form.obligation"
                        min="1" 
                        max="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                    @error('form.obligation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Energy (Optional) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Energy (1-10)</label>
                    <input 
                        type="number" 
                        wire:model="form.energy"
                        min="1" 
                        max="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                    @error('form.energy') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Deadline --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                    <input 
                        type="datetime-local" 
                        wire:model="form.deadline"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                    @error('form.deadline') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="form.status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required
                    >
                        <option value="todo">Todo</option>
                        <option value="doing">Doing</option>
                        <option value="done">Done</option>
                        <option value="hold">Hold</option>
                        <option value="dropped">Dropped</option>
                    </select>
                    @error('form.status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex space-x-3 mt-6">
                <button 
                    type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    {{ $editing ? 'Update' : 'Simpan' }}
                </button>
                
                <button 
                    type="button"
                    wire:click="resetForm"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none"
                >
                    Reset
                </button>

                @if($editing)
                    <button 
                        type="button"
                        wire:click="archive({{ $editing->id }})"
                        wire:confirm="Yakin ingin mengarsipkan task ini?"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none"
                    >
                        Arsipkan
                    </button>

                    <button 
                        type="button"
                        wire:click="delete({{ $editing->id }})"
                        wire:confirm="Yakin ingin menghapus task ini?"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none"
                    >
                        Hapus
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Task List --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Task</h2>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tasks as $task)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="text-xs text-gray-500">{{ Str::limit($task->description, 40) }}</div>
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-indigo-600">{{ number_format($task->priority_score, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $task->deadline ? $task->deadline->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button 
                                wire:click="edit({{ $task->id }})"
                                class="text-indigo-600 hover:text-indigo-900"
                            >
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Belum ada task
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Event Notifications --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('task-created', (event) => {
                alert('Task berhasil dibuat!');
            });
            Livewire.on('task-updated', (event) => {
                alert('Task berhasil diupdate!');
            });
            Livewire.on('task-deleted', (event) => {
                alert('Task berhasil dihapus!');
            });
            Livewire.on('task-archived', (event) => {
                alert('Task berhasil diarsipkan!');
            });
        });
    </script>
</div>
