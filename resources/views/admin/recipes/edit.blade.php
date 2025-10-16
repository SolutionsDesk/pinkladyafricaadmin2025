<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Recipe: {{ $recipe->title }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Call the component, passing both the recipe and country_code --}}
                    <x-recipe-form :recipe="$recipe" :country_code="$country_code" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
