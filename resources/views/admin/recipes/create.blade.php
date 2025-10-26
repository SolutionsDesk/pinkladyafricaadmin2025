<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create New Recipe ({{ strtoupper($country_code) }})
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Call the component, passing the country_code --}}
                    <x-recipe-form :country_code="$country_code" />
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
