<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <section class="task-lists">

        @if (session()->has('message'))
            <div class="alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:p-6 lg:p-8">
            
            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-4">Task</div>
                <div class="col-span-2 text-center">Start Date</div>
                <div class="col-span-2 text-center">Due Date</div>
                <div class="col-span-2 text-center">Status</div>
                <div Class="col-span-2 text-center">Action</div>


                @foreach ($tasks as $task)
                <div class="col-span-4 my-auto">{{ $task->name }}</div>
                <div class="col-span-2 my-auto text-center">{{ $task->date_start }}</div>
                <div class="col-span-2 my-auto text-center">{{ $task->date_due }}</div>
                <div class="col-span-2 my-auto font-bold py-1 px-4 text-center rounded">{{ $task->status ? $task->status->name : '' }}</div>
                <div Class="col-span-2 my-auto text-center flex">
                    <form action="{{ route('tasksold.edit', ['tasksold' => $task->id]) }}" method="GET">
                        <x-button class="ml-3">
                            {{ __('Edit') }}
                        </x-button>
                    </form>
                    <form action="{{ route('tasksold.destroy', ['tasksold' => $task->id]) }}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <x-button-danger class="ml-3">
                            {{ __('Delete') }}
                        </x-button-danger>
                    </form>
                </div>
                @endforeach
                
                <div class="col-span-12">{{ $tasks->links() }}</div>
            </div>

            
            

            <form action="{{ route('tasksold.create') }}" method="GET">
                <x-button class="mt-8">
                    {{ __('Add Task') }}
                </x-button>
            </form>
        </div>
    </section>

</x-app-layout>