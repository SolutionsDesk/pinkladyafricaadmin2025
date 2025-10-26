<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create New Healthy Living Post ({{ strtoupper($country_code) }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.country.healthy-living.store', $country_code) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Title</label>
                                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Body</label>
                                <textarea id="body" name="body" rows="10" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 font-mono">{{ old('body') }}</textarea>
                                @error('body') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.country.healthy-living.index', $country_code) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Create Post</button>
                        </div>
                    </form>
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
