<x-page-section>
    
    <x-slot name="title">{{ $user->currentTeam->name }}'s {{ __('Task Lists') }}</x-slot>
    <x-slot name="description">{{ __('Manage the teams current lists') }}</x-slot>

    <x-slot name="content">
        
        <div class="col-span-full shadow bg-gray-200 rounded flex gap-0.5 flex-col">
        
            @foreach ($taskLists as $taskList)
                <x-tasks.tasklist-item :listItem="$taskList" />
            @endforeach
        
        </div>

    </x-slot>

</x-page-section>