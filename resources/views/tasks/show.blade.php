@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl>
                <div class="sm:flex sm:items-center sm:justify-between border-b border-gray-200 py-4 px-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:ml-4">{{ $task->description }}</dd>
                </div>

                <div class="sm:flex sm:items-center sm:justify-between border-b border-gray-200 py-4 px-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Due Date:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:ml-4">{{ $task->due_date }}</dd>
                </div>

                <div class="sm:flex sm:items-center sm:justify-between border-b border-gray-200 py-4 px-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:ml-4">{{ $task->status }}</dd>
                </div>

                <div class="sm:flex sm:items-center sm:justify-between border-b border-gray-200 py-4 px-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Category:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:ml-4">{{ $task->category }}</dd>
                </div>

                <div class="sm:flex sm:items-center sm:justify-between border-b border-gray-200 py-4 px-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Priority:</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:ml-4">{{ $task->priority }}</dd>
                </div>
            </dl>
        </div>
        <div class="px-4 py-4 sm:px-6">
            <a href="{{ route('tasks.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Back to List</a>
        </div>
    </div>
</div>
@endsection