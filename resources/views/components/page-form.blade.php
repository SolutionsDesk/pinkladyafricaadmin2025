@props(['page' => null, 'country_code'])

@php
    $isEdit = isset($page);
    $action = $isEdit
        ? route('admin.country.pages.update', ['country_code' => $country_code, 'page' => $page])
        : route('admin.country.pages.store', $country_code);

    $templates = collect([
        'default' => 'Default Template', 'home' => 'Homepage', 'contact' => 'Contact',
        'retailers' => 'Retailers', 'suppliers' => 'Suppliers', 'find' => 'Find Pink Lady Apples',
        'healthy' => 'Healthy Living', 'recipes' => 'Recipes', 'story' => 'Our Story'
    ])->sortBy(fn($value, $key) => $value)->toArray();
@endphp

{{-- Add Alpine variables for the new "Grown with Love" section --}}
{{ html()->model($page)->form('POST', $action)->attributes(['x-data' => "pageForm({
    banners: " . json_encode(old('content.banners', $page->content['banners'] ?? [])) . ",
    selectedTemplate: '" . old('template_name', $page->template_name ?? 'default') . "',
    info1BgPreview: null,
    info1BgExisting: " . json_encode($page->content['info_1_bg']['path'] ?? null) . ",
    info2BgPreview: null,
    info2BgExisting: " . json_encode($page->content['info_2_bg']['path'] ?? null) . ",
    grownImagePreview: null,
    grownImageExisting: " . json_encode($page->content['grown_image']['path'] ?? null) . ",
    image1Preview: null,
    image1Existing: " . json_encode($page->content['image_1']['path'] ?? null) . ",
    image2Preview: null,
    image2Existing: " . json_encode($page->content['image_2']['path'] ?? null) . ",
    image3Preview: null,
    image3Existing: " . json_encode($page->content['image_3']['path'] ?? null) . "
})", 'enctype' => 'multipart/form-data'])->open() }}

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

        <div x-show="selectedTemplate === 'home'" x-transition class="space-y-6 border-t border-gray-200 dark:border-gray-700 pt-6">

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Homepage Info Box 1</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        <template x-if="info1BgExisting && !info1BgPreview"><img :src="'/storage/' + info1BgExisting" class="h-24 w-auto rounded-md object-cover"></template>
                        <template x-if="info1BgPreview"><img :src="info1BgPreview" class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[info_1_bg]')->attributes(['@change' => 'setInfo1Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.info_1_bg') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[info_1_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[info_1_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_1_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[info_1_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[info_1_content]')->rows(5)->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_1_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Homepage Info Box 2</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        <template x-if="info2BgExisting && !info2BgPreview"><img :src="'/storage/' + info2BgExisting" class="h-24 w-auto rounded-md object-cover"></template>
                        <template x-if="info2BgPreview"><img :src="info2BgPreview" class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[info_2_bg]')->attributes(['@change' => 'setInfo2Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.info_2_bg') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[info_2_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[info_2_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_2_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[info_2_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[info_2_content]')->rows(5)->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_2_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">"Grown with Love" Section</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        <template x-if="grownImageExisting && !grownImagePreview"><img :src="'/storage/' + grownImageExisting" class="h-24 w-auto rounded-md object-cover"></template>
                        <template x-if="grownImagePreview"><img :src="grownImagePreview" class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[grown_image]')->attributes(['@change' => 'setGrownImagePreview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.grown_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[grown_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[grown_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.grown_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[grown_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[grown_content]')->rows(5)->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.grown_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6">
                    <h4 class="font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Feature Images</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Packed with goodness</label>
                            <div class="mt-1">
                                <template x-if="image1Existing && !image1Preview"><img :src="'/storage/' + image1Existing" class="h-24 w-24 rounded-md object-cover"></template>
                                <template x-if="image1Preview"><img :src="image1Preview" class="h-24 w-24 rounded-md object-cover"></template>
                            </div>
                            {{ html()->file('content[image_1]')->attributes(['@change' => 'setImage1Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                            @error('content.image_1') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Healthy living</label>
                            <div class="mt-1">
                                <template x-if="image2Existing && !image2Preview"><img :src="'/storage/' + image2Existing" class="h-24 w-24 rounded-md object-cover"></template>
                                <template x-if="image2Preview"><img :src="image2Preview" class="h-24 w-24 rounded-md object-cover"></template>
                            </div>
                            {{ html()->file('content[image_2]')->attributes(['@change' => 'setImage2Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                            @error('content.image_2') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Competitions</label>
                            <div class="mt-1">
                                <template x-if="image3Existing && !image3Preview"><img :src="'/storage/' + image3Existing" class="h-24 w-24 rounded-md object-cover"></template>
                                <template x-if="image3Preview"><img :src="image3Preview" class="h-24 w-24 rounded-md object-cover"></template>
                            </div>
                            {{ html()->file('content[image_3]')->attributes(['@change' => 'setImage3Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                            @error('content.image_3') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-sm">
            <div>
                {{ html()->label('Template', 'template_name')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                {{ html()->select('template_name', $templates)->id('template_name')->attributes(['x-model' => 'selectedTemplate'])->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700') }}
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
            selectedTemplate: initialData.selectedTemplate,
            info1BgPreview: initialData.info1BgPreview,
            info1BgExisting: initialData.info1BgExisting,
            info2BgPreview: initialData.info2BgPreview,
            info2BgExisting: initialData.info2BgExisting,
            grownImagePreview: initialData.grownImagePreview,
            grownImageExisting: initialData.grownImageExisting,
            image1Preview: initialData.image1Preview,
            image1Existing: initialData.image1Existing,
            image2Preview: initialData.image2Preview,
            image2Existing: initialData.image2Existing,
            image3Preview: initialData.image3Preview,
            image3Existing: initialData.image3Existing,

            addBanner() {
                this.banners.push({ image_url: '', title: '', description: '', new_image_preview: null });
            },
            setPreview(event, index) {
                const file = event.target.files[0];
                if (file) { this.banners[index].new_image_preview = URL.createObjectURL(file); }
            },
            setInfo1Preview(event) {
                const file = event.target.files[0];
                if(file) { this.info1BgPreview = URL.createObjectURL(file); }
            },
            setInfo2Preview(event) {
                const file = event.target.files[0];
                if(file) { this.info2BgPreview = URL.createObjectURL(file); }
            },
            setGrownImagePreview(event) {
                const file = event.target.files[0];
                if(file) { this.grownImagePreview = URL.createObjectURL(file); }
            },
            setImage1Preview(event) {
                const file = event.target.files[0];
                if(file) { this.image1Preview = URL.createObjectURL(file); }
            },
            setImage2Preview(event) {
                const file = event.target.files[0];
                if(file) { this.image2Preview = URL.createObjectURL(file); }
            },
            setImage3Preview(event) {
                const file = event.target.files[0];
                if(file) { this.image3Preview = URL.createObjectURL(file); }
            },
        }));
    });
</script>
