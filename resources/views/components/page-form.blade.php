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

    // --- ADDED THIS LINE ---
    // Get the base URL from your 'digitalocean' disk config
    $storageUrl = rtrim(Storage::disk('digitalocean')->url('/'), '/');
@endphp

{{ html()->model($page)->form('POST', $action)->attributes(['x-data' => "pageForm({
    // --- ADDED THIS LINE ---
    storageBaseUrl: " . json_encode($storageUrl) . ",

    banners: " . json_encode(old('content.banners', $page->content['banners'] ?? [])) . ",
    goodnessList: " . json_encode(old('content.goodness_list', $page->content['goodness_list'] ?? [])) . ",
    healthyList: " . json_encode(old('content.healthy_list', $page->content['healthy_list'] ?? [])) . ", // <-- NEW List
    selectedTemplate: '" . old('template_name', $page->template_name ?? 'default') . "',
    info1BgPreview: null,
    info1BgExisting: " . json_encode($page->content['info_1_bg']['path'] ?? null) . ",
    info2BgPreview: null,
    info2BgExisting: " . json_encode($page->content['info_2_bg']['path'] ?? null) . ",
    info3BgPreview: null,
    info3BgExisting: " . json_encode($page->content['info_3_bg']['path'] ?? null) . ",
    grownImagePreview: null,
    grownImageExisting: " . json_encode($page->content['grown_image']['path'] ?? null) . ",
    image1Preview: null,
    image1Existing: " . json_encode($page->content['image_1']['path'] ?? null) . ",
    image2Preview: null,
    image2Existing: " . json_encode($page->content['image_2']['path'] ?? null) . ",
    image3Preview: null,
    image3Existing: " . json_encode($page->content['image_3']['path'] ?? null) . ",
    recipeBgPreview: null,
    recipeBgExisting: " . json_encode($page->content['recipe_bg_image']['path'] ?? null) . ",
    birthBgPreview: null,
    birthBgExisting: " . json_encode($page->content['birth_bg_image']['path'] ?? null) . ",
    goodnessBgPreview: null,
    goodnessBgExisting: " . json_encode($page->content['goodness_bg_image']['path'] ?? null) . ",
    healthyBgPreview: null, // <-- NEW Preview
    healthyBgExisting: " . json_encode($page->content['healthy_bg_image']['path'] ?? null) . " // <-- NEW Existing
})", 'enctype' => 'multipart/form-data'])->open() }}

@if($isEdit)
    @method('PUT')
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div>
            {{ html()->label('Title', 'title')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
            {{ html()->text('title')->id('title')->required()->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
            @error('title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div x-show="!['story', 'retailers', 'suppliers', 'find'].includes(selectedTemplate)">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Banners</h3>
            <div class="space-y-4">
                <template x-for="(banner, index) in banners" :key="index">
                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold" x-text="'Banner ' + (index + 1)"></h4>
                            <button @click.prevent="banners.splice(index, 1)" type="button"
                                    class="text-red-500 hover:text-red-700 text-sm">Remove
                            </button>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                                <template x-if="banner.image_url && !banner.new_image_preview">
                                    <div class="mt-2">
                                        {{-- --- MODIFIED THIS LINE --- --}}
                                        <img :src="storageBaseUrl + '/' + (banner.image_url.path || banner.image_url)"
                                             class="h-24 w-auto rounded-md object-cover">
                                    </div>
                                </template>
                                <template x-if="banner.new_image_preview">
                                    <div class="mt-2">
                                        <img :src="banner.new_image_preview"
                                             class="h-24 w-auto rounded-md object-cover">
                                    </div>
                                </template>
                                <input type="file" :name="'content[banners][' + index + '][image_url]'"
                                       @change="setPreview($event, index)"
                                       class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                            </div>
                            <div>
                                <label :for="'banner_title_' + index"
                                       class="text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" :name="'content[banners][' + index + '][title]'"
                                       x-model="banner.title"
                                       class="block mt-1 w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                            <div>
                                <label :for="'banner_desc_' + index"
                                       class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea :name="'content[banners][' + index + '][description]'"
                                          x-model="banner.description" rows="2"
                                          class="block mt-1 w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <button @click.prevent="addBanner()" type="button"
                    class="mt-4 text-sm inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                Add Banner
            </button>
        </div>
        {{-- Homepage Template sections --}}
        <div x-show="selectedTemplate === 'home'" x-transition
             class="space-y-6 border-t border-gray-200 dark:border-gray-700 pt-6">

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Homepage Info Box 1</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="info1BgExisting && !info1BgPreview"><img :src="storageBaseUrl + '/' + info1BgExisting"
                                                                                 class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="info1BgPreview"><img :src="info1BgPreview"
                                                             class="h-24 w-auto rounded-md object-cover"></template>
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
                    {{ html()->textarea('content[info_1_content]')->rows(5)->class('block mt-1 w-full rounded-md txt-lg shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_1_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Homepage Info Box 2</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="info2BgExisting && !info2BgPreview"><img :src="storageBaseUrl + '/' + info2BgExisting"
                                                                                 class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="info2BgPreview"><img :src="info2BgPreview"
                                                             class="h-24 w-auto rounded-md object-cover"></template>
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
                    {{ html()->textarea('content[info_2_content]')->rows(5)->class('block mt-1 w-full txt-lg rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_2_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Homepage Info Box 3</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="info3BgExisting && !info3BgPreview"><img :src="storageBaseUrl + '/' + info3BgExisting"
                                                                                 class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="info3BgPreview"><img :src="info3BgPreview"
                                                             class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[info_3_bg]')->attributes(['@change' => 'setInfo3Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.info_3_bg') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[info_3_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[info_3_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_3_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[info_3_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[info_3_content]')->rows(5)->class('block mt-1 w-full txt-lg rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.info_3_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">"Grown with Love" Section</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="grownImageExisting && !grownImagePreview"><img
                                :src="storageBaseUrl + '/' + grownImageExisting" class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="grownImagePreview"><img :src="grownImagePreview"
                                                                class="h-24 w-auto rounded-md object-cover"></template>
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
                    {{ html()->textarea('content[grown_content]')->rows(5)->class('block mt-1 w-full txt-lg rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.grown_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div class="mt-6">
                    <h4 class="font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Feature Images</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Packed with goodness</label>
                            <div class="mt-1">
                                {{-- --- MODIFIED THIS LINE --- --}}
                                <template x-if="image1Existing && !image1Preview"><img
                                        :src="storageBaseUrl + '/' + image1Existing" class="h-24 w-24 rounded-md object-cover">
                                </template>
                                <template x-if="image1Preview"><img :src="image1Preview"
                                                                    class="h-24 w-24 rounded-md object-cover">
                                </template>
                            </div>
                            {{ html()->file('content[image_1]')->attributes(['@change' => 'setImage1Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                            @error('content.image_1') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Healthy living</label>
                            <div class="mt-1">
                                {{-- --- MODIFIED THIS LINE --- --}}
                                <template x-if="image2Existing && !image2Preview"><img
                                        :src="storageBaseUrl + '/' + image2Existing" class="h-24 w-24 rounded-md object-cover">
                                </template>
                                <template x-if="image2Preview"><img :src="image2Preview"
                                                                    class="h-24 w-24 rounded-md object-cover">
                                </template>
                            </div>
                            {{ html()->file('content[image_2]')->attributes(['@change' => 'setImage2Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                            @error('content.image_2') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400">Competitions</label>
                            <div class="mt-1">
                                {{-- --- MODIFIED THIS LINE --- --}}
                                <template x-if="image3Existing && !image3Preview"><img
                                        :src="storageBaseUrl + '/' + image3Existing" class="h-24 w-24 rounded-md object-cover">
                                </template>
                                <template x-if="image3Preview"><img :src="image3Preview"
                                                                    class="h-24 w-24 rounded-md object-cover">
                                </template>
                            </div>
                            {{ html()->file('content[image_3]')->attributes(['@change' => 'setImage3Preview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                            @error('content.image_3') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recipes Section</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="recipeBgExisting && !recipeBgPreview"><img :src="storageBaseUrl + '/' + recipeBgExisting"
                                                                                   class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="recipeBgPreview"><img :src="recipeBgPreview"
                                                              class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[recipe_bg_image]')->attributes(['@change' => 'setRecipePreview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.recipe_bg_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[recipe_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[recipe_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.recipe_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[recipe_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[recipe_content]')->rows(5)->class('block mt-1 w-full rounded-md txt-lg shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.recipe_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

        </div>


        {{-- Our Story template sections --}}
        <div x-show="selectedTemplate === 'story'" x-transition
             class="space-y-6 border-t border-gray-200 dark:border-gray-700 pt-6">

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Story Information</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="birthBgExisting && !birthBgPreview"><img :src="storageBaseUrl + '/' + birthBgExisting"
                                                                                 class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="birthBgPreview"><img :src="birthBgPreview"
                                                             class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[birth_bg_image]')->attributes(['@change' => 'setBirthBgPreview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.birth_bg_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[birth_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[birth_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.birth_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[birth_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[birth_content]')->rows(5)->class('block mt-1 w-full rounded-md txt-lg shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.birth_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Packed with Goodness</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- --- MODIFIED THIS LINE --- --}}
                        <template x-if="goodnessBgExisting && !goodnessBgPreview"><img
                                :src="storageBaseUrl + '/' + goodnessBgExisting" class="h-24 w-auto rounded-md object-cover">
                        </template>
                        <template x-if="goodnessBgPreview"><img :src="goodnessBgPreview"
                                                                class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{ html()->file('content[goodness_bg_image]')->attributes(['@change' => 'setGoodnessBgPreview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.goodness_bg_image') <p
                        class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Title', 'content[goodness_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[goodness_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.goodness_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{ html()->label('Content', 'content[goodness_content]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->textarea('content[goodness_content]')->rows(5)->class('block mt-1 w-full rounded-md txt-lg shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.goodness_content') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div class="mt-6">
                    <h4 class="font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Goodness List</h4>
                    <div class="space-y-2">
                        <template x-for="(item, index) in goodnessList" :key="index">
                            <div class="flex items-center space-x-2">
                                <input type="text" :name="'content[goodness_list][' + index + '][text]'"
                                       x-model="item.text"
                                       class="block w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                                <button @click.prevent="goodnessList.splice(index, 1)" type="button"
                                        class="text-red-500 hover:text-red-700">Remove
                                </button>
                            </div>
                        </template>
                    </div>
                    <button @click.prevent="addGoodnessItem()" type="button"
                            class="mt-2 text-sm inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Add List Item
                    </button>
                </div>
            </div>

        </div>
        {{-- End of Our Story Template section --}}

        <div x-show="selectedTemplate === 'healthy'" x-transition class="space-y-6 border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Apple Facts Section</h3> {{-- Renamed Heading --}}
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Background Image</label>
                    <div class="mt-2">
                        {{-- Renamed Alpine Vars --}}
                        <template x-if="healthyBgExisting && !healthyBgPreview"><img :src="storageBaseUrl + '/' + healthyBgExisting" class="h-24 w-auto rounded-md object-cover"></template>
                        <template x-if="healthyBgPreview"><img :src="healthyBgPreview" class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    {{-- Renamed Input Name, Alpine Function, Error Key --}}
                    {{ html()->file('content[healthy_bg_image]')->attributes(['@change' => 'setHealthyBgPreview($event)'])->class('block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer') }}
                    @error('content.healthy_bg_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
                <div>
                    {{-- Renamed Input Name, Error Key --}}
                    {{ html()->label('Title', 'content[healthy_title]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                    {{ html()->text('content[healthy_title]')->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                    @error('content.healthy_title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6">
                    <h4 class="font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Apple Facts</h4> {{-- Renamed Heading --}}
                    <div class="space-y-2">
                        {{-- Renamed Alpine List Var --}}
                        <template x-for="(item, index) in healthyList" :key="index">
                            <div class="flex items-center space-x-2">
                                {{-- Renamed Input Name --}}
                                <input type="text" :name="'content[healthy_list][' + index + '][text]'" x-model="item.text" class="block w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                                {{-- Renamed Alpine List Var --}}
                                <button @click.prevent="healthyList.splice(index, 1)" type="button" class="text-red-500 hover:text-red-700">Remove</button>
                            </div>
                        </template>
                    </div>
                    {{-- Renamed Alpine Function --}}
                    <button @click.prevent="addHealthyItem()" type="button" class="mt-2 text-sm inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Add List Item</button>
                </div>
            </div>
        </div>
        {{-- End of Healthy Living Template section --}}
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
            <a href="{{ route('admin.country.pages.index', $country_code) }}"
               class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Cancel</a>
            <button type="submit"
                    class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                {{ $isEdit ? 'Update Page' : 'Create Page' }}
            </button>
        </div>
    </div>
</div>
{{ html()->form()->close() }}

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pageForm', (initialData) => ({
            // --- ADDED THIS LINE ---
            storageBaseUrl: initialData.storageBaseUrl,

            banners: initialData.banners.map(banner => ({...banner, new_image_preview: null})),
            goodnessList: initialData.goodnessList.map(item => ({...item})), // New repeater data
            healthyList: initialData.healthyList.map(item => ({...item})), // New repeater data
            selectedTemplate: initialData.selectedTemplate,
            info1BgPreview: initialData.info1BgPreview,
            info1BgExisting: initialData.info1BgExisting,
            info2BgPreview: initialData.info2BgPreview,
            info2BgExisting: initialData.info2BgExisting,
            info3BgPreview: initialData.info3BgPreview,
            info3BgExisting: initialData.info3BgExisting,
            grownImagePreview: initialData.grownImagePreview,
            grownImageExisting: initialData.grownImageExisting,
            image1Preview: initialData.image1Preview,
            image1Existing: initialData.image1Existing,
            image2Preview: initialData.image2Preview,
            image2Existing: initialData.image2Existing,
            image3Preview: initialData.image3Preview,
            image3Existing: initialData.image3Existing,
            recipeBgPreview: initialData.recipeBgPreview,
            recipeBgExisting: initialData.recipeBgExisting,
            birthBgPreview: initialData.birthBgPreview,
            birthBgExisting: initialData.birthBgExisting,
            goodnessBgPreview: initialData.goodnessBgPreview,
            goodnessBgExisting: initialData.goodnessBgExisting,
            healthyBgPreview: initialData.healthyBgPreview, // <-- NEW Preview Init
            healthyBgExisting: initialData.healthyBgExisting, // <-- NEW Existing Init

            addBanner() {
                this.banners.push({image_url: '', title: '', description: '', new_image_preview: null});
            },
            setPreview(event, index) {
                const file = event.target.files[0];
                if (file) {
                    this.banners[index].new_image_preview = URL.createObjectURL(file);
                }
            },
            setInfo1Preview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.info1BgPreview = URL.createObjectURL(file);
                }
            },
            setInfo2Preview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.info2BgPreview = URL.createObjectURL(file);
                }
            },
            setInfo3Preview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.info3BgPreview = URL.createObjectURL(file);
                }
            },
            setGrownImagePreview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.grownImagePreview = URL.createObjectURL(file);
                }
            },
            setImage1Preview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.image1Preview = URL.createObjectURL(file);
                }
            },
            setImage2Preview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.image2Preview = URL.createObjectURL(file);
                }
            },
            setImage3Preview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.image3Preview = URL.createObjectURL(file);
                }
            },
            setRecipePreview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.recipeBgPreview = URL.createObjectURL(file);
                }
            },
            addGoodnessItem() {
                this.goodnessList.push({text: ''});
            },
            setBirthBgPreview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.birthBgPreview = URL.createObjectURL(file);
                }
            },
            setGoodnessBgPreview(event) {
                const file = event.target.files[0];
                if (file) {
                    this.goodnessBgPreview = URL.createObjectURL(file);
                }
            },

            addHealthyItem() { this.healthyList.push({text: ''}); }, // <-- NEW Add Item
            setHealthyBgPreview(event) { // <-- NEW Set Preview
                const file = event.target.files[0];
                if (file) { this.healthyBgPreview = URL.createObjectURL(file); }
            },
        }));
    });
</script>
