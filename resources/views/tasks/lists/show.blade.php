<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h1>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-5 px-3 sm:px-6 lg:px-8">
            @livewire('tasks.lists.show', ['list' => $list])
        </div>
    </div>

    @livewire('tasks.show-task-modal')
</x-app-layout>
