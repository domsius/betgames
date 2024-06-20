@extends('layouts.app')

@section('content')
<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold leading-6 text-gray-900">Categories</h1>
            <a href="{{ route('categories.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Category</a>
        </div>

        @if ($categories->isEmpty())
            <p class="text-gray-500">No categories found.</p>
        @else
            <div class="-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" class="pl-4 pr-3 py-3.5 text-left text-xs font-medium text-gray-900 sm:pl-0">Name</th>
                                <th scope="col" class="pr-4 py-3.5 text-right">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($categories as $category)
                            <tr>
                                <td class="pl-4 pr-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sm:pl-0">{{ $category->name }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->description }}</td>
                                <td class="pr-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-900 ml-2">Edit</a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection