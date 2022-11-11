<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Lists') }}
        </h1>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            
            @livewire('tasks.task-list-form')
            <x-jet-section-border />

            @livewire('tasks.show-task-lists', ['taskLists' => $taskLists])

        </div>
    </div>
</x-app-layout>
