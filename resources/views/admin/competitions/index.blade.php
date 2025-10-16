<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Competitions ({{ strtoupper($country_code) }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="text-gray-900 dark:text-gray-100">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-semibold">Competition List</h3>
                            <a href="{{ route('admin.country.competitions.create', $country_code) }}" class="inline-flex items-center px-4 py-2 bg-green-600 ...">
                                Create New Competition
                            </a>
                        </div>
                    </div>
                    {{-- ... Success Message ... --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left ...">Title</th>
                                <th class="px-6 py-3 text-left ...">Start Date</th>
                                <th class="px-6 py-3 text-left ...">End Date</th>
                                <th class="px-6 py-3 text-right ...">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($competitions as $competition)
                                <tr>
                                    <td class="px-6 py-4 ...">{{ $competition->title }}</td>
                                    <td class="px-6 py-4 ...">{{ $competition->start_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 ...">{{ $competition->end_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right ...">
                                        <a href="{{ route('admin.country.competitions.edit', ['country_code' => $country_code, 'competition' => $competition]) }}" class="text-indigo-600 ...">Edit</a>
                                        <form action="{{ route('admin.country.competitions.destroy', ['country_code' => $country_code, 'competition' => $competition]) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 ...">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center ...">No competitions found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
