@props(['page' => null, 'country_code'])

@php
    $isEdit = isset($page);
    $action = $isEdit
        ? route('admin.country.pages.update', ['country_code' => $country_code, 'page' => $page])
        : route('admin.country.pages.store', $country_code);

    $templates = [
        'default' => 'Default Template', 'home' => 'Homepage', 'contact' => 'Contact',
        'retailers' => 'Retailers', 'suppliers' => 'Suppliers', 'find' => 'Find Pink Lady Apples',
        'healthy' => 'Healthy Living', 'recipes' => 'Recipes', 'story' => 'Our Story'
    ];
    asort($templates);
@endphp

{{-- Add 'enctype' => 'multipart/form-data' to the attributes array --}}
{{ html()->model($page)->form('POST', $action)->attributes(['x-data' => "pageForm({ banners: " . json_encode(old('content.banners', $page->content['banners'] ?? [])) . " })", 'enctype' => 'multipart/form-data'])->open() }}
@if($isEdit) @method('PUT') @endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div>
            {{ html()->label('Title', 'title')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
            {{ html()->text('title')->id('title')->required()->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
            @error('title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Banners</h3>
            <div class="space-y-4">
                <template x-for="(banner, index) in banners" :key="index">
                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold" x-text="'Banner ' + (index + 1)"></h4>
                            <button @click.prevent="banners.splice(index, 1)" type="button" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>

                                <template x-if="banner.image_url && !banner.new_image_preview">
                                    <div class="mt-2">
                                        <img :src="'/storage/' + (banner.image_url.path || banner.image_url)" class="h-24 w-auto rounded-md object-cover">
                                    </div>
                                </template>

                                <template x-if="banner.new_image_preview">
                                    <div class="mt-2">
                                        <img :src="banner.new_image_preview" class="h-24 w-auto rounded-md object-cover">
                                    </div>
                                </template>

                                <input type="file" :name="'content[banners][' + index + '][image_url]'" @change="setPreview($event, index)" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                            </div>

                            <div>
                                <label :for="'banner_title_' + index" class="text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" :name="'content[banners][' + index + '][title]'" x-model="banner.title" class="block mt-1 w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>

                            <div>
                                <label :for="'banner_desc_' + index" class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea :name="'content[banners][' + index + '][description]'" x-model="banner.description" rows="2" class="block mt-1 w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <button @click.prevent="addBanner()" type="button" class="mt-4 text-sm inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                Add Banner
            </button>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-sm">
            <div>
                {{ html()->label('Template', 'template_name')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                {{ html()->select('template_name', $templates)->id('template_name')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700') }}
                @error('template_name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            @if($isEdit)
                <div class="mt-4">
                    {{ html()->label('Slug', 'slug')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('slug')->id('slug')->disabled()->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-800 dark:border-gray-700') }}
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('admin.country.pages.index', $country_code) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Cancel</a>
            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ $isEdit ? 'Update Page' : 'Create Page' }}
            </button>
        </div>
    </div>
</div>
{{ html()->form()->close() }}

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pageForm', (initialData) => ({
            banners: initialData.banners.map(banner => ({...banner, new_image_preview: null})),
            addBanner() {
                this.banners.push({ image_url: '', title: '', description: '', new_image_preview: null });
            },
            setPreview(event, index) {
                const file = event.target.files[0];
                if (file) {
                    this.banners[index].new_image_preview = URL.createObjectURL(file);
                } else {
                    this.banners[index].new_image_preview = null;
                }
            }
        }));
    });
</script>
