<x-page-section>
    
    <x-slot name="title">{{ $user->currentTeam->name }}'s {{ __('Task Lists') }}</x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="content">
        
        <div class="col-span-full border border-gray-200 shadow rounded flex gap-1 flex-col">
        
            @foreach ($taskLists as $taskList)
                <x-tasks.lists.single-item 
                    wire:key="taskList-{{ $taskList['id'] }}"
                    :listItem="$taskList"
                />
            @endforeach
        
        </div>

    </x-slot>

</x-page-section>