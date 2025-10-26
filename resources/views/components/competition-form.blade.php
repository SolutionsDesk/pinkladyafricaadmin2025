@props(['competition' => null, 'country_code'])

@php
    $isEdit = isset($competition);
    $action = $isEdit
        ? route('admin.country.competitions.update', ['country_code' => $country_code, 'competition' => $competition])
        : route('admin.country.competitions.store', $country_code);
@endphp

<form x-data="{ bgPreview: null, bgExisting: {{ json_encode($competition->content['bg_image']['path'] ?? null) }} }" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="space-y-6">
        <div>
            <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title', $competition->title ?? '') }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
            @error('title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Start Date</label>
                {{-- Use null-safe operators --}}
                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $competition?->start_date?->format('Y-m-d')) }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                @error('start_date') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">End Date</label>
                {{-- Use null-safe operators --}}
                <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $competition?->end_date?->format('Y-m-d')) }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                @error('end_date') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
            <div class="mt-2">
                <template x-if="bgExisting && !bgPreview"><img :src="'/storage/' + bgExisting" class="h-24 w-auto rounded-md object-cover"></template>
                <template x-if="bgPreview"><img :src="bgPreview" class="h-24 w-auto rounded-md object-cover"></template>
            </div>
            <input type="file" name="content[bg_image]" @change="bgPreview = URL.createObjectURL($event.target.files[0])" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
            @error('content.bg_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="competition_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Competition Name</label>
            <input id="competition_name" type="text" name="content[competition_name]" value="{{ old('content.competition_name', $competition->content['competition_name'] ?? '') }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
            @error('content.competition_name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Body</label>
            <textarea id="body" name="content[body]" rows="10" class="block mt-1 w-full rounded-md text-lg shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">{{ old('content.body', $competition->content['body'] ?? '') }}</textarea>
            @error('content.body') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center justify-end mt-6">
        <a href="{{ route('admin.country.competitions.index', $country_code) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Cancel</a>
        <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
            {{ $isEdit ? 'Update Competition' : 'Create Competition' }}
        </button>
    </div>
</form>
