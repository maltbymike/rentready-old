<x-page-section>

    <x-slot name="title">{{ $list->name }}</x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="content">

        <div class="col-span-full rounded flex flex-col">
        
            @foreach ($list->tasks as $key => $task)

                <x-single-item 
                    wire:key="task-{{ $task['id'] }}"
                    wire:click.prevent="$emit('showTask', {{ $task['id'] }})"
                    disabled="{{ $task->is_closed }}">

                    <x-slot name="start">

                        <input type="checkbox" 
                            wire:click="closeOrOpenTask({{ $task }})"
                            wire:model="list.tasks.{{$key}}.is_closed"
                            class="m-2 text-primary focus:ring-primary rounded-lg"
                            title="Close Task" />
                    
                        </x-slot>

                    {{ $task->name }} 

                    <div 
                        class="flex text-gray-500 items-center border 
                            hover:bg-white hover:fill-primary hover:text-primary
                            peer-active:bg-gray-900 peer-active:text-white" 
                        title="{{ $task['tasks_count'] }} Tasks">

                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">

                            <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M151.6 469.6C145.5 476.2 137 480 128 480s-17.5-3.8-23.6-10.4l-88-96c-11.9-13-11.1-33.3 2-45.2s33.3-11.1 45.2 2L96 365.7V64c0-17.7 14.3-32 32-32s32 14.3 32 32V365.7l32.4-35.4c11.9-13 32.2-13.9 45.2-2s13.9 32.2 2 45.2l-88 96zM320 32h32c17.7 0 32 14.3 32 32s-14.3 32-32 32H320c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 128h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H320c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 128H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H320c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 128H544c17.7 0 32 14.3 32 32s-14.3 32-32 32H320c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/>
                        
                        </svg>

                        <div class="text-xs">{{ $task['tasks_count'] }}</div>

                    </div>

                    <x-slot name="end">

                        <div class="flex items-center gap-3">

                            <x-tasks.status-select-dropdown
                                wire:change="changeTaskStatus({{ $task->id }}, $event.target.value)"
                                wire:model="list.tasks.{{ $key }}.task_status_id"
                                :current="$task->task_status_id"
                                :statuses="$list->statuses" />
                            
                            <button 
                                wire:click="$emit('loadTaskList', '{{ $task['id'] }}')"
                                class="h-4 w-4 m-2 hover:fill-primary active:fill-gray-900" 
                                title="Task List Settings">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336c44.2 0 80-35.8 80-80s-35.8-80-80-80s-80 35.8-80 80s35.8 80 80 80z"/></svg>
                            
                            </button>
                        
                        </div>
                    
                    </x-slot>

                </x-single-item>
            
            @endforeach
        
        </div>

    </x-slot>

</x-page-section>