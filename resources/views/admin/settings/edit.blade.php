<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Site Settings ({{ strtoupper($country_code) }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"
             x-data="{ footerLogoPreview: null, footerLogoExisting: {{ json_encode($setting->settings['footer_details']['footer_logo']['path'] ?? null) }} }">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.country.settings.update', $country_code) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-8">

                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h3 class="text-lg font-medium">Social Media</h3>
                            <div>
                                <label for="facebook_url" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Facebook URL</label>
                                <input type="url" name="settings[facebook_url]" id="facebook_url" value="{{ old('settings.facebook_url', $setting->settings['facebook_url'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                            <div>
                                <label for="instagram_url" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Instagram URL</label>
                                <input type="url" name="settings[instagram_url]" id="instagram_url" value="{{ old('settings.instagram_url', $setting->settings['instagram_url'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                        </div>

                        <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h3 class="text-lg font-medium">Contact Person</h3>
                            <div>
                                <label for="contact_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="settings[contact_person][name]" id="contact_name" value="{{ old('settings.contact_person.name', $setting->settings['contact_person']['name'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                            <div>
                                <label for="contact_cell" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Cell Number</label>
                                <input type="text" name="settings[contact_person][cell]" id="contact_cell" value="{{ old('settings.contact_person.cell', $setting->settings['contact_person']['cell'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                            <div>
                                <label for="contact_email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="settings[contact_person][email]" id="contact_email" value="{{ old('settings.contact_person.email', $setting->settings['contact_person']['email'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                        </div>

                        <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h3 class="text-lg font-medium">Contact Form Queries</h3>
                            <div>
                                <label for="admin_email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Admin email address to send to</label>
                                <input type="email" name="settings[contact_form][admin_email]" id="admin_email" value="{{ old('settings.contact_form.admin_email', $setting->settings['contact_form']['admin_email'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                        </div>

                        <div class="space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <h3 class="text-lg font-medium">Footer Details</h3>
                            <div>
                                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Footer Logo</label>
                                <div class="mt-2">
                                    <template x-if="footerLogoExisting && !footerLogoPreview"><img :src="'/storage/' + footerLogoExisting" class="h-24 w-auto rounded-md object-cover"></template>
                                    <template x-if="footerLogoPreview"><img :src="footerLogoPreview" class="h-24 w-auto rounded-md object-cover"></template>
                                </div>
                                <input type="file" name="settings[footer_details][footer_logo]" @change="footerLogoPreview = URL.createObjectURL($event.target.files[0])" class="block mt-2 text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 cursor-pointer">
                            </div>
                            <div>
                                <label for="catch_line" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Catch Line</label>
                                <input type="text" name="settings[footer_details][catch_line]" id="catch_line" value="{{ old('settings.footer_details.catch_line', $setting->settings['footer_details']['catch_line'] ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            </div>
                            <div>
                                <label for="cookie_consent" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Cookie Consent</label>
                                <textarea name="settings[footer_details][cookie_consent]" id="cookie_consent" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700">{{ old('settings.footer_details.cookie_consent', $setting->settings['footer_details']['cookie_consent'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end p-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
