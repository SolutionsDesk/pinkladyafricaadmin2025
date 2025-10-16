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

                    <div class="mt-6 space-y-6">
                        @auth
                            {{-- Webmaster sees all sections --}}
                            @if(auth()->user()->hasRole('webmaster'))
                                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border dark:border-gray-600">
                                    <h4 class="font-bold text-lg">Global</h4>
                                    <div class="mt-2 space-y-2">
                                        <a href="{{ route('users.index') }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Users & Roles
                                        </a>
                                    </div>
                                </div>

                                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border dark:border-gray-600">
                                    <h4 class="font-bold text-lg">🇰🇪 Kenya</h4>
                                    <div class="mt-2 space-y-2">
                                        <a href="{{ route('admin.country.pages.index', ['country_code' => 'ke']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Pages
                                        </a>
                                        <a href="{{ route('admin.country.recipes.index', ['country_code' => 'ke']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Recipes
                                        </a>
                                        <a href="{{ route('admin.country.healthy-living.index', ['country_code' => 'ke']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Healthy Living
                                        </a>
                                        <a href="{{ route('admin.country.competitions.index', ['country_code' => 'ke']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Competitions
                                        </a>
                                        <a href="{{ route('admin.country.settings.edit', ['country_code' => 'ke']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Site Settings
                                        </a>
                                    </div>
                                </div>

                                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border dark:border-gray-600">
                                    <h4 class="font-bold text-lg">🇳🇬 Nigeria</h4>
                                    <div class="mt-2 space-y-2">
                                        <a href="{{ route('admin.country.pages.index', ['country_code' => 'ng']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Pages
                                        </a>
                                        <a href="{{ route('admin.country.recipes.index', ['country_code' => 'ng']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Recipes
                                        </a>
                                        <a href="{{ route('admin.country.healthy-living.index', ['country_code' => 'ng']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Healthy Living
                                        </a>
                                        <a href="{{ route('admin.country.competitions.index', ['country_code' => 'ng']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Competitions
                                        </a>
                                        <a href="{{ route('admin.country.settings.edit', ['country_code' => 'ng']) }}"
                                           class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                            → Manage Site Settings
                                        </a>
                                    </div>
                                </div>

                                {{-- Kenyan editors only see their links --}}
                            @elseif(auth()->user()->hasRole('pl-kenya'))
                                <a href="{{ route('admin.country.pages.index', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Pages
                                </a>
                                <a href="{{ route('admin.country.recipes.index', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Recipes
                                </a>
                                <a href="{{ route('admin.country.healthy-living.index', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Healthy Living
                                </a>
                                <a href="{{ route('admin.country.competitions.index', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Competitions
                                </a>
                                <a href="{{ route('admin.country.settings.edit', ['country_code' => 'ke']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Site Settings
                                </a>

                                {{-- Nigerian editors only see their links --}}
                            @elseif(auth()->user()->hasRole('pl-nigeria'))
                                <a href="{{ route('admin.country.pages.index', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Pages
                                </a>
                                <a href="{{ route('admin.country.recipes.index', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Recipes
                                </a>
                                <a href="{{ route('admin.country.healthy-living.index', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Healthy Living
                                </a>
                                <a href="{{ route('admin.country.competitions.index', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Competitions
                                </a>
                                <a href="{{ route('admin.country.settings.edit', ['country_code' => 'ng']) }}"
                                   class="block font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-100">
                                    → Manage Site Settings
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
