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

        <script>
            let taskDialog=document.getElementById("task-dialog")

            window.addEventListener('showTaskDialog', event => {
                showTaskDialog();
            })

            window.addEventListener('hideTaskDialog', event => {
                taskDialog.close();
            })

            function showTaskDialog(){
                taskDialog.showModal();
            }

            function hideTaskDialog(){
                taskDialog.close();
            }

        </script>
    @endpush

    <section class="task-lists bg-slate-900 text-white h-full">

        <div class="max-w-7xl mx-auto">
            <div class="flex w-full justify-between">
                <div class="p-3">{{ __('Tasks') }}</div>
                <x-button wire:click.prevent="toggleShowClosed" class="m-1">
                    @empty($showClosed)
                        {{ __('Show Completed') }}
                    @else
                        {{ __('Hide Completed') }}
                    @endempty
                </x-button>
            </div>

            <div class="bg-slate-800">
                @if ($tasksOverdue->isNotEmpty())
                    <div class="w-full p-3 pr-0">{{ __('Overdue') }}
                        @foreach ($tasksOverdue as $taskInList)
                        <div class="flex">
                            <button wire:click="updateTaskStatusClosed({{ $taskInList->id }})" class="grow-0 px-3 flex justify-items-center items-center" aria-label="Mark task with task name: {{ $taskInList->name }} Closed">
                                <svg class="h-6 w-6 border-2 border-gray-300 rounded {{ $taskInList->status->id == $taskStatusClosed ? 'fill-green-600' : 'fill-transparent' }} fill-gray-500 hover:border-green-300 hover:fill-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" /></svg>
                            </button>
                            <div wire:click="edit({{ $taskInList->id }})" wire:key="todayTask-{{ $taskInList->id }}" class="grow w-full flex rounded hover:bg-gray-700">
                                <div class="grow my-auto p-3 {{ $taskInList->status->id == $taskStatusClosed ? 'line-through' : '' }}">{{ $taskInList->name }}</div>
                                <div class="grow-0 my-auto font-bold py-1 px-3 rounded text-right">{{ $taskInList->status ? $taskInList->status->name : '' }}</div>
                            </div>
                            
                        </div>
                        @endforeach
                    </div>
                @endif

                @if ($tasksToday->isNotEmpty())
                    <div class="w-full p-3 pr-0">{{ __('Today') }}
                        @foreach ($tasksToday as $taskInList)
                        <div class="flex">
                            <div wire:click="edit({{ $taskInList->id }})" wire:key="todayTask-{{ $taskInList->id }}" class="grow w-full flex rounded hover:bg-gray-700">
                                <div class="grow my-auto p-3 {{ $taskInList->status->id == $taskStatusClosed ? 'line-through' : '' }}">{{ $taskInList->name }}</div>
                                <div class="grow-0 my-auto font-bold py-1 px-3 rounded text-right">{{ $taskInList->status ? $taskInList->status->name : '' }}</div>
                            </div>
                            <button wire:click="updateTaskStatusClosed({{ $taskInList->id }})" class="grow-0 px-3 flex justify-items-center items-center" aria-label="Mark task with task name: {{ $taskInList->name }} Closed">
                                <svg class="h-6 w-6 border-2 border-gray-300 rounded {{ $taskInList->status->id == $taskStatusClosed ? 'fill-green-600' : 'fill-transparent' }} fill-gray-500 hover:border-green-300 hover:fill-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @endif
                
                @if ($tasksTomorrow->isNotEmpty())
                    <div class="w-full p-3 pr-0">{{ __('Tomorrow') }}
                        @foreach ($tasksTomorrow as $taskInList)
                        <div class="flex">
                            <div wire:click="edit({{ $taskInList->id }})" wire:key="todayTask-{{ $taskInList->id }}" class="grow w-full flex rounded hover:bg-gray-700">
                                <div class="grow my-auto p-3 {{ $taskInList->status->id == $taskStatusClosed ? 'line-through' : '' }}">{{ $taskInList->name }}</div>
                                <div class="grow-0 my-auto font-bold py-1 px-3 rounded text-right">{{ $taskInList->status ? $taskInList->status->name : '' }}</div>
                            </div>
                            <button wire:click="updateTaskStatusClosed({{ $taskInList->id }})" class="grow-0 px-3 flex justify-items-center items-center" aria-label="Mark task with task name: {{ $taskInList->name }} Closed">
                                <svg class="h-6 w-6 border-2 border-gray-300 rounded {{ $taskInList->status->id == $taskStatusClosed ? 'fill-green-600' : 'fill-transparent' }} fill-gray-500 hover:border-green-300 hover:fill-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @endif

                @if ($tasksFuture->isNotEmpty())
                    <div class="w-full p-3 pr-0">{{ __('Upcoming') }}
                        @foreach ($tasksFuture as $taskInList)
                        <div class="flex">
                            <div wire:click="edit({{ $taskInList->id }})" wire:key="todayTask-{{ $taskInList->id }}" class="grow w-full flex rounded hover:bg-gray-700">
                                <div class="grow my-auto p-3 {{ $taskInList->status->id == $taskStatusClosed ? 'line-through' : '' }}">{{ $taskInList->name }}</div>
                                <div class="grow-0 my-auto font-bold py-1 px-3 rounded text-right">{{ $taskInList->status ? $taskInList->status->name : '' }}</div>
                            </div>
                            <button wire:click="updateTaskStatusClosed({{ $taskInList->id }})" class="grow-0 px-3 flex justify-items-center items-center" aria-label="Mark task with task name: {{ $taskInList->name }} Closed">
                                <svg class="h-6 w-6 border-2 border-gray-300 rounded {{ $taskInList->status->id == $taskStatusClosed ? 'fill-green-600' : 'fill-transparent' }} fill-gray-500 hover:border-green-300 hover:fill-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @endif

                @if ($tasksWithNoDueDate->isNotEmpty())
                    <div class="w-full p-3 pr-0">{{ __('No Due Date Set') }}
                        @foreach ($tasksWithNoDueDate as $taskInList)
                        <div class="flex">
                            <div wire:click="edit({{ $taskInList->id }})" wire:key="todayTask-{{ $taskInList->id }}" class="grow w-full flex rounded hover:bg-gray-700">
                                <div class="grow my-auto p-3 {{ $taskInList->status->id == $taskStatusClosed ? 'line-through' : '' }}">{{ $taskInList->name }}</div>
                                <div class="grow-0 my-auto font-bold py-1 px-3 rounded text-right">{{ $taskInList->status ? $taskInList->status->name : '' }}</div>
                            </div>
                            <button wire:click="updateTaskStatusClosed({{ $taskInList->id }})" class="grow-0 px-3 flex justify-items-center items-center" aria-label="Mark task with task name: {{ $taskInList->name }} Closed">
                                <svg class="h-6 w-6 border-2 border-gray-300 rounded {{ $taskInList->status->id == $taskStatusClosed ? 'fill-green-600' : 'fill-transparent' }} fill-gray-500 hover:border-green-300 hover:fill-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" /></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                @endif

                <x-button wire:click.prevent="resetFormFields" class="mt-8 w-full">
                    {{ __('+ Add Task') }}
                </x-button>
            </div>
        </div>

    </section>

    <dialog id="task-dialog">
    <!-- <x-jet-dialog-modal wire:model="showTaskDialog"> -->

        <section name="content" class="task-form">

            <div wire:loading.grid wire:target="edit, resetFormFields, save" class="h-96 max-h-screen place-items-center">
                <span class="spinner"></span>
            </div>
            
            <div wire:loading.remove wire:target="edit, resetFormFields, save" class="w-full max-w-7xl mx-auto">
                <div class="grid grid-cols-12 gap-x-3 gap-y-8">

                    <div x-data class="col-span-12 flex justify-end">
                        <div x-on:click="$wire.closeTaskDialog()" class="close-button-container group relative w-6 h-6 cursor-pointer flex justify-center">
                            <div class="leftright bg-black h-[2px] w-6 absolute mt-[24px] rounded rotate-45 group-hover:-rotate-45 transition-all duration-300 ease-in"></div>
                            <div class="rightleft bg-black h-[2px] w-6 absolute mt-[24px] rounded -rotate-45 group-hover:rotate-45 transition-all duration-300 ease-in"></div>                       
                            <label class="close mt-8 mx-auto absolute text-[.6em] uppercase tracking-wide opacity-0 group-hover:opacity-100 transition-all duration-300 ease-in">close</label>
                        </div>
                    </div>

                    <div class="mb-3 col-span-3">
                        <div class="w-full flex gap-3">
                            <x-forms.form-group class="grow">
                                <x-forms.select id="task_status_id" name="task_status_id" wire:model="currentTask.statusId">
                                    @foreach ($taskStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </x-forms.select>
                                <x-forms.label for="task_status_id">{{ __('Status') }}</x-forms.label>
                            </x-forms.form-group>
                            
                            @if (isset($currentTask['statusId']) && $currentTask['statusId'] != $taskStatusClosed)
                                <button wire:click="updateTaskStatusClosed({{ isset($currentTask['id']) ? $currentTask['id'] : '' }})" class="grow-0 flex justify-items-center items-center" aria-label="Mark task closed">
                                    <svg class="h-10 w-10 border-2 border-gray-300 rounded fill-gray-500 hover:border-green-300 hover:fill-green-600 p-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                                </button>
                            @endif
                            
                        </div>

                        @error ('currentTask.statusId') 
                            <p id="task_status_id_error_help" class="mt-2 text-xs w-full text-red-600 basis-full">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-span-3">
                        <x-forms.form-group>
                            <x-forms.select id="task_repeats" name="task_repeats" wire:model="currentTask.repeats">
                                <option value=""></option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="periodically">Periodically</option>                                
                            </x-forms.select>
                            <x-forms.label for="task_repeats">{{ __('Repeats') }}</x-forms.label>
                        </x-forms.form-group>
                    </div>

                    <div class="mb-3 col-span-3">
                        <x-forms.form-group>
                            <x-forms.input id="date_start" name="date_start" type="date" wire:model="currentTask.dateStart" />
                            <x-forms.label for="date_start">{{ __('Start Date') }}</x-forms.label>
                        </x-forms.form-group>
                        @error ('currentTask.dateStart') 
                            <p id="date_start_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-span-3">
                        <x-forms.form-group>
                            <x-forms.input id="date_due" name="date_due" type="date" wire:model="currentTask.dateDue" />
                            <x-forms.label for="date_due">{{ __('Due Date') }}</x-forms.label>
                        </x-forms.form-group>
                        @error ('currentTask.dateDue') 
                            <p id="date_due_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8 col-span-12">
                        <x-forms.form-group>
                            <x-forms.input type="text" id="name" name="name" wire:model="currentTask.name" />
                            <x-forms.label for="name">{{ __('Task Name') }}</x-forms.label>
                        </x-forms.form-group>
                        @error ('currentTask.name')
                            <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-span-12">
                        <x-forms.form-group>
                            <div x-data="{ currentTask: @entangle('currentTask').defer }">
                                <input id="details" name="details" type="hidden" x-model="currentTask.details" />
                                <div wire:ignore>
                                <trix-editor class="trix-editor" x-model.debounce.300ms="currentTask.details"></trix-editor>
                                </div>
                                @error ('currentTask.details') 
                                    <p id="details_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-forms.label for="details">{{ __('Details') }}</x-forms.label>

                        </x-forms.form-group>
                    </div>

                    <h2>Subtasks</h2>
                    @isset($currentTask['subtasks'])
                        <x-laravel-blade-sortable::sortable 
                            class="col-span-12 grid grid-cols-12 gap-x-3 gap-y-8 p-0"
                            drag-handle="drag-handle"
                            wire:onSortOrderChange="handleSortOrderChange"
                        >
                        @foreach ($currentTask['subtasks'] as $key => $value)
                            <x-laravel-blade-sortable::sortable-item 
                                sort-key="{{ $value['id'] }}" 
                                wire:key="subtask-{{ $value['id'] }}"
                                class="col-span-12 flex gap-x-3 items-center hover:bg-gray-100 p-2"
                            >
                                <svg class="drag-handle w-5 h-5 fill-gray-200 hover:fill-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M17.5 40q-1.45 0-2.475-1.025Q14 37.95 14 36.5q0-1.45 1.025-2.475Q16.05 33 17.5 33q1.45 0 2.475 1.025Q21 35.05 21 36.5q0 1.45-1.025 2.475Q18.95 40 17.5 40Zm13 0q-1.45 0-2.475-1.025Q27 37.95 27 36.5q0-1.45 1.025-2.475Q29.05 33 30.5 33q1.45 0 2.475 1.025Q34 35.05 34 36.5q0 1.45-1.025 2.475Q31.95 40 30.5 40Zm-13-12.5q-1.45 0-2.475-1.025Q14 25.45 14 24q0-1.45 1.025-2.475Q16.05 20.5 17.5 20.5q1.45 0 2.475 1.025Q21 22.55 21 24q0 1.45-1.025 2.475Q18.95 27.5 17.5 27.5Zm13 0q-1.45 0-2.475-1.025Q27 25.45 27 24q0-1.45 1.025-2.475Q29.05 20.5 30.5 20.5q1.45 0 2.475 1.025Q34 22.55 34 24q0 1.45-1.025 2.475Q31.95 27.5 30.5 27.5ZM17.5 15q-1.45 0-2.475-1.025Q14 12.95 14 11.5q0-1.45 1.025-2.475Q16.05 8 17.5 8q1.45 0 2.475 1.025Q21 10.05 21 11.5q0 1.45-1.025 2.475Q18.95 15 17.5 15Zm13 0q-1.45 0-2.475-1.025Q27 12.95 27 11.5q0-1.45 1.025-2.475Q29.05 8 30.5 8q1.45 0 2.475 1.025Q34 10.05 34 11.5q0 1.45-1.025 2.475Q31.95 15 30.5 15Z"/></svg>
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
                            </x-laravel-blade-sortable::sortable-item>
                        @endforeach
                        </x-laravel-blade-sortable::sortable>
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

        </section>

    <!-- </x-jet-dialog-modal> -->
    </dialog>
</div>