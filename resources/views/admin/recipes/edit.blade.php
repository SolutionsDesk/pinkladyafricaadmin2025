<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Recipe: {{ $recipe->title }}
        </h2>
    </x-slot>

    {{-- --- ADD THIS ERROR NOTIFICATION BLOCK --- --}}
    @if ($errors->any())
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-24 right-6 w-full max-w-md bg-red-600 text-white rounded-lg shadow-lg p-4 z-50">

            <div class="flex justify-between items-center mb-2">
                <h4 class="font-bold text-lg">Whoops! Something went wrong.</h4>
                <button @click="show = false" class="text-xl font-bold leading-none">&times;</button>
            </div>

            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- --- END OF NOTIFICATION BLOCK --- --}}

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

    <script src="{{ asset('assets/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Config for SMALL editors (.txt-sm) ---
            tinymce.init({
                selector: 'textarea.txt-sm',
                height: 300,
                license_key: 'gpl',
                menubar: false,
                base_url: '{{ asset('assets/tinymce') }}',

                // --- DARK MODE SETTINGS ---
                skin: 'oxide-dark',
                content_css: 'dark',
                // --- ------------------ ---

                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'paste', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',

            });

            // --- Config for LARGE editors (.txt-lg) ---
            tinymce.init({
                selector: 'textarea.txt-lg',
                height: 500,
                license_key: 'gpl',
                menubar: true,
                base_url: '{{ asset('assets/tinymce') }}',

                // --- DARK MODE SETTINGS ---
                skin: 'oxide-dark',
                content_css: 'dark',
                // --- ------------------ ---

                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'paste', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | '+
                    'removeformat | help',

            });

        });
    </script>
</x-app-layout>
