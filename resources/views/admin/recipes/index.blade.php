<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Recipe Management ({{ strtoupper($country_code) }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="text-gray-900 dark:text-gray-100">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-semibold">Recipe List</h3>
                            <a href="{{ route('admin.country.recipes.create', $country_code) }}" class="inline-flex items-center px-4 py-2 bg-green-600 ...">
                                Create New Recipe
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="m-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs ...">Title</th>
                                <th class="px-6 py-3 text-right text-xs ...">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recipes as $recipe)
                                <tr>
                                    <td class="px-6 py-4 ...">{{ $recipe->title }}</td>
                                    <td class="px-6 py-4 text-right ...">
                                        <a href="{{ route('admin.country.recipes.edit', ['country_code' => $country_code, 'recipe' => $recipe]) }}" class="text-indigo-600 ...">Edit</a>
                                        <form action="{{ route('admin.country.recipes.destroy', ['country_code' => $country_code, 'recipe' => $recipe]) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 ...">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center ...">No recipes found for this country.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
