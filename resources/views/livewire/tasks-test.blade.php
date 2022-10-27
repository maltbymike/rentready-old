<div>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h1>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
        <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>

        <script>
            Sortable.create(subtasksList, { /* options */ });
        </script>
    @endpush

            <div class="grid grid-cols-12 gap-x-3 gap-y-8" style="max-height: 75vh; overflow-y:auto; overflow-x:hidden;">

                <h2>Subtasks</h2>
                @isset($currentTask['subtasks'])
                    <div id="subtasksList" class="list-group col-span-12 grid grid-cols-12 gap-x-3 gap-y-8 p-0">
                    @foreach ($currentTask['subtasks'] as $key => $value)
                        <div class="list-group-item col-span-12 flex gap-x-3 items-center hover:bg-gray-100 p-2">
                            <svg class="w-5 h-5 fill-gray-200 hover:fill-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M17.5 40q-1.45 0-2.475-1.025Q14 37.95 14 36.5q0-1.45 1.025-2.475Q16.05 33 17.5 33q1.45 0 2.475 1.025Q21 35.05 21 36.5q0 1.45-1.025 2.475Q18.95 40 17.5 40Zm13 0q-1.45 0-2.475-1.025Q27 37.95 27 36.5q0-1.45 1.025-2.475Q29.05 33 30.5 33q1.45 0 2.475 1.025Q34 35.05 34 36.5q0 1.45-1.025 2.475Q31.95 40 30.5 40Zm-13-12.5q-1.45 0-2.475-1.025Q14 25.45 14 24q0-1.45 1.025-2.475Q16.05 20.5 17.5 20.5q1.45 0 2.475 1.025Q21 22.55 21 24q0 1.45-1.025 2.475Q18.95 27.5 17.5 27.5Zm13 0q-1.45 0-2.475-1.025Q27 25.45 27 24q0-1.45 1.025-2.475Q29.05 20.5 30.5 20.5q1.45 0 2.475 1.025Q34 22.55 34 24q0 1.45-1.025 2.475Q31.95 27.5 30.5 27.5ZM17.5 15q-1.45 0-2.475-1.025Q14 12.95 14 11.5q0-1.45 1.025-2.475Q16.05 8 17.5 8q1.45 0 2.475 1.025Q21 10.05 21 11.5q0 1.45-1.025 2.475Q18.95 15 17.5 15Zm13 0q-1.45 0-2.475-1.025Q27 12.95 27 11.5q0-1.45 1.025-2.475Q29.05 8 30.5 8q1.45 0 2.475 1.025Q34 10.05 34 11.5q0 1.45-1.025 2.475Q31.95 15 30.5 15Z"/></svg>
                            <button 
                                wire:click.prevent="updateTaskStatusClosed({{ $value['id'] }})"
                                wire:key="updateSubtaskButton-{{ $value['id'] }}"
                                class="grow-0 flex justify-items-center items-center h-6 w-6 border-2 border-gray-300 rounded {{ $value['task_status_id'] == $taskStatusClosed ? 'fill-green-600' : 'fill-white' }} hover:border-green-300 hover:fill-green-600" 
                                aria-label="Mark task closed"
                            >
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                            </button>
                            <x-forms.form-group class="grow">
                                <x-forms.input 
                                    type="text" 
                                    id="currentSubtask.{{ $key }}.name" 
                                    class="{{ $value['task_status_id'] == $taskStatusClosed ? 'line-through' : '' }}"
                                    wire:model.lazy="currentTask.subtasks.{{ $key }}.name"
                                    disabled
                                />
                                <x-forms.label for="currentSubtask.{{ $key }}.name" class="hidden">{{ __('Subtask Name') }}</x-forms.label>
                            </x-forms.form-group>
                            <button wire:click.prevent="edit({{ $value['id'] }})" class="flex justify-items-center items-center h-6 w-6 fill-gray-300 hover:fill-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                            </button>
                            @error ('subtask.{{ $value }}.name')
                                <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                    </div>
                @endisset

                @foreach ($subtasksNew as $key => $value)
                    <div class="col-span-12 flex">
                        <x-forms.form-group class="grow">
                            <x-forms.input type="text" id="subtask.{{ $value }}.name" name="subtask.{{ $value }}.name" wire:model.lazy="subtask.{{ $value }}.name" />
                            <x-forms.label for="subtask.{{ $value }}.name">{{ __('Subtask Name') }}</x-forms.label>
                        </x-forms.form-group>
                        <x-button-danger wire:click.prevent="subtasksRemove({{ $key }})">x</x-button>
                        @error ('subtask.{{ $value }}.name')
                            <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="col-span-12">
                    <x-button wire:click.prevent="subtasksAdd({{ $i }})" class="">+ Add Subtask</x-button>
                </div>
                </div>

                <div class="mt-6 flex justify-end w-full gap-3">

                    <x-button-danger wire:click.prevent="resetFormFields">
                        {{ __('Cancel') }}
                    </x-button-danger>

                    <x-button wire:click.prevent="save({{ isset($currentTask['id']) ? $currentTask['id'] : '' }})" class="">
                        @empty($updateMode)
                            {{ __('Save') }}
                        @else
                            {{ __('Update') }}
                        @endempty
                    </x-button>

                    <x-button-outline wire:click.prevent="save({{ isset($currentTask['id']) ? $currentTask['id'] : 'null' }}, true)" class="">
                        @empty($updateMode)
                            {{ __('Save and Close') }}
                        @else
                            {{ __('Update and Close') }}
                        @endempty
                    </x-button>
                </div>
            </div>
        
</div>