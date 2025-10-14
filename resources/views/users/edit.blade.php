<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email') <p class="text-red-500 text-xs mt-2">{{ $message }}</p @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="roles" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Roles</label>
                            <select name="roles[]" id="roles" multiple
                                    class="block mt-1 w-full max-w-[600px] rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" @if($user->hasRole($role->name)) selected @endif>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Hold down Command (Mac) or Control (Windows) to select multiple roles.</p>
                            @error('roles') <p class="text-red-500 text-xs mt-2">{{ $message }}</p @enderror
                        </div>

                        <hr class="my-6 border-gray-200 dark:border-gray-700">

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Change Password (Optional)</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Leave these fields blank to keep the current password.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">New Password</label>
                                    <input id="password" type="password" name="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('password') <p class="text-red-500 text-xs mt-2">{{ $message }}</p @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Confirm New Password</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
