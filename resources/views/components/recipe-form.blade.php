@props(['recipe' => null, 'country_code'])

@php
    $isEdit = isset($recipe);
    $action = $isEdit
        ? route('admin.country.recipes.update', ['country_code' => $country_code, 'recipe' => $recipe])
        : route('admin.country.recipes.store', $country_code);
@endphp

<form x-data="recipeForm({
    ingredients: {{ json_encode(old('content.ingredients', $recipe->content['ingredients'] ?? [])) }},
    bannerPreview: null,
    bannerExisting: {{ json_encode($recipe->content['banner_image']['path'] ?? null) }},
    chefLogoPreview: null,
    chefLogoExisting: {{ json_encode($recipe->content['chef_logo']['path'] ?? null) }}
})" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            <div>
                <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $recipe->title ?? null) }}" required class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                @error('title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Banner</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Banner Image</label>
                    <div class="mt-2">
                        <template x-if="bannerExisting && !bannerPreview"><img :src="'/storage/' + bannerExisting" class="h-24 w-auto rounded-md object-cover"></template>
                        <template x-if="bannerPreview"><img :src="bannerPreview" class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    <input type="file" name="content[banner_image]" @change="setBannerPreview($event)" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                    @error('content.banner_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recipe Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="serves" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Serves</label>
                        <input type="text" name="content[serves]" id="serves" value="{{ old('content.serves', $recipe->content['serves'] ?? null) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>
                    <div>
                        <label for="cooking_time" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Cooking Time</label>
                        <input type="text" name="content[cooking_time]" id="cooking_time" value="{{ old('content.cooking_time', $recipe->content['cooking_time'] ?? null) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Chef (Optional)</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chef Logo</label>
                    <div class="mt-2">
                        <template x-if="chefLogoExisting && !chefLogoPreview"><img :src="'/storage/' + chefLogoExisting" class="h-24 w-auto rounded-md object-cover"></template>
                        <template x-if="chefLogoPreview"><img :src="chefLogoPreview" class="h-24 w-auto rounded-md object-cover"></template>
                    </div>
                    <input type="file" name="content[chef_logo]" @change="setChefLogoPreview($event)" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                </div>
                <div>
                    <label for="chef_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chef Name</label>
                    <input type="text" name="content[chef_name]" id="chef_name" value="{{ old('content.chef_name', $recipe->content['chef_name'] ?? null) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                </div>
                <div>
                    <label for="chef_website" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Chef Website</label>
                    <input type="url" name="content[chef_website]" id="chef_website" value="{{ old('content.chef_website', $recipe->content['chef_website'] ?? null) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recipe Files</h3>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Recipe PDF</label>
                    @if($isEdit && !empty($recipe->content['recipe_pdf']['name']))
                        <p class="text-xs text-gray-500 mt-1">Current file: {{ $recipe->content['recipe_pdf']['name'] }}</p>
                    @endif
                    <input type="file" name="content[recipe_pdf]" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Recipe Video (Optional)</label>
                    @if($isEdit && !empty($recipe->content['recipe_video']['name']))
                        <p class="text-xs text-gray-500 mt-1">Current file: {{ $recipe->content['recipe_video']['name'] }}</p>
                    @endif
                    <input type="file" name="content[recipe_video]" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                </div>
                <div>
                    <label for="video_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Video Name (Optional)</label>
                    <input type="text" name="content[video_name]" id="video_name" value="{{ old('content.video_name', $recipe->content['video_name'] ?? null) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                </div>
            </div>

            <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ingredients</h3>
                <div class="space-y-2">
                    <template x-for="(item, index) in ingredients" :key="index">
                        <div class="flex items-center space-x-2">
                            <input type="text" :name="'content[ingredients][' + index + '][ingredient]'" x-model="item.ingredient" placeholder="Ingredient text..." class="block w-full text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            <select :name="'content[ingredients][' + index + '][type]'" x-model="item.type" class="block text-sm rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                                <option value="ingredient">Ingredient</option>
                                <option value="heading">Heading</option>
                            </select>
                            <button @click.prevent="ingredients.splice(index, 1)" type="button" class="text-red-500 hover:text-red-700 text-sm p-2">Remove</button>
                        </div>
                    </template>
                </div>
                <button @click.prevent="addIngredient()" type="button" class="mt-2 text-sm inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Add Ingredient
                </button>
            </div>
            <div>
                {{ html()->label('Method', 'content[method]')->class('block font-medium text-sm text-gray-700 dark:text-gray-300') }}
                {{ html()->textarea('content[method]', old('content.method', $recipe->content['method'] ?? null))->rows(5)->class('block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700') }}
                @error('content.method') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow-sm">
                @if($isEdit)
                    <div class="mt-4">
                        <label for="slug" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Slug</label>
                        <input type="text" id="slug" value="{{ $recipe->slug }}" disabled class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                    </div>
                @endif
            </div>
            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('admin.country.recipes.index', $country_code) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Cancel</a>
                <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ $isEdit ? 'Update Recipe' : 'Create Recipe' }}
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('recipeForm', (initialData) => ({
            ingredients: initialData.ingredients.map(item => ({...item})),
            bannerPreview: initialData.bannerPreview,
            bannerExisting: initialData.bannerExisting,
            chefLogoPreview: initialData.chefLogoPreview,
            chefLogoExisting: initialData.chefLogoExisting,
            addIngredient() {
                this.ingredients.push({ ingredient: '', type: 'ingredient' });
            },
            setBannerPreview(event) {
                const file = event.target.files[0];
                if(file) { this.bannerPreview = URL.createObjectURL(file); }
            },
            setChefLogoPreview(event) {
                const file = event.target.files[0];
                if(file) { this.chefLogoPreview = URL.createObjectURL(file); }
            },
        }));
    });
</script>
