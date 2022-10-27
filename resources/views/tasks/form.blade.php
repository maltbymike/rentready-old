<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <section class="task-form">

        @if (session()->has('message'))
            <div class="alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:p-6 lg:p-8">

            <form action="/tasks/{{ isset($task) ? $task->id : '' }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                @if (isset($task))
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                @endif

                <div class="grid grid-cols-12 gap-x-3 gap-y-8">

                    <div class="mb-8 col-span-12">
                        <x-forms.form-group>
                            <x-forms.input type="text" id="name" name="name" value="{{ old('name', isset($task) ? $task->name : '') }}" />
                            <x-forms.label for="name">Task Name:</x-forms.label>
                        </x-forms.form-group>
                        @error ('name')
                            <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-span-3">
                        <div class="w-full flex gap-3">
                            <x-forms.form-group class="grow">
                                <x-forms.select id="task_status_id" name="task_status_id">
                                    @foreach ($taskStatuses as $status)
                                        <option value={{ $status->id }}
                                            @if ($status->id == old('task_status_id', isset($task) ? $task->status->id : ''))
                                                selected="selected"
                                            @endif
                                            >
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                                <x-forms.label for="task_status_id">Status</x-forms.label>
                            </x-forms.form-group>
                            
                            @if (isset($task) ? $task->status->id : '' != $taskStatusClosed)
                                <button wire:click="updateTaskStatusClosed" class="grow-0 flex justify-items-center items-center">
                                    <svg class="h-full w-10 border-2 border-gray-300 rounded fill-gray-500 hover:border-green-300 hover:fill-green-600 p-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                                </button>
                            @endif
                        </div>

                        @error ('task_status_id') 
                            <p id="task_status_id_error_help" class="mt-2 text-xs w-full text-red-600 basis-full">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-3 col-start-7 col-span-3">
                        <x-forms.form-group>
                            <x-forms.input id="date_start" name="date_start" type="date" value="{{ old('date_start', isset($task) ? $task->date_start : '') }}" />
                            <x-forms.label for="date_start">Start Date</x-forms.label>
                        </x-forms.form-group>
                        @error ('date_start') 
                            <p id="date_start_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-span-3">
                        <x-forms.form-group>
                            <x-forms.input id="date_due" name="date_due" type="date" value="{{ old('date_due', isset($task) ? $task->date_due : '') }}" />
                            <x-forms.label for="date_due">Due Date</x-forms.label>
                        </x-forms.form-group>
                        @error ('date_due') 
                            <p id="date_due_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-span-12">
                        @livewire('editorjs', [
                            'editorId' => "detailsEditor",
                            'value' => $task->details,
                            'uploadDisk' => 'public',
                            'downloadDisk' => 'public',
                            'class' => '...',
                            'readOnly' => false,
                            'placeholder' => 'Description or [tab] for options'
                        ])

                        <x-forms.form-group>
                            <x-forms.textarea class="min-h-fit h-48" name="details" id="details">{{ old('details', isset($task) ? $task->details : '') }}</x-forms.textarea>
                            <x-forms.label for="details">Details</x-forms.label>
                        </x-forms.form-group>
                        @error ('details') 
                            <p id="details_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                    </div>
                </div>

                @if ($updateMode)
                    <input type="hidden" name="_method" value="PUT">
                    
                    <x-button-danger name="submit" value="cancel">
                        {{ __('Cancel') }}
                    </x-button-danger>

                    <x-button class="ml-3" name="submit" value="update">
                        {{ __('Update') }}
                    </x-button>
                @else
                    <x-button class="" name="submit" value="store">
                        {{ __('Save') }}
                    </x-button>
                @endif
            </form>
        </div>
    </section>
</x-app-layout>