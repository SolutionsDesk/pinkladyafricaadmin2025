<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold border-b border-gray-200 dark:border-gray-700 pb-4">
                        Content Management
                    </h3>

                    <div class="mt-6 space-y-4">
                        @auth
                            {{-- Webmaster sees links to all country admin areas --}}
                            @if(auth()->user()->hasRole('webmaster'))
                                <a href="{{ route('admin.country.pages.index', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Kenya Pages
                                </a>
                                <a href="{{ route('admin.country.pages.index', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Nigeria Pages
                                </a>

                                {{-- Kenyan editors only see the link to the Kenya admin area --}}
                            @elseif(auth()->user()->hasRole('pl-kenya'))
                                <a href="{{ route('admin.country.pages.index', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Kenya Pages
                                </a>

                                {{-- Nigerian editors only see the link to the Nigeria admin area --}}
                            @elseif(auth()->user()->hasRole('pl-nigeria'))
                                <a href="{{ route('admin.country.pages.index', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Nigeria Pages
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
