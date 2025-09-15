<x-app-layout>
    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 ">
            <h1 class="text-3xl font-bold mb-4 text-center">Create Post</h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- Image -->
                    <x-input-label for="image" :value="__('Image')" />
                    <x-text-input id="image" class="block mt-1 w-full" type="file"
                        name="image" :value="old('image')"  autofocus />
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />

                    <!-- Category -->
                    <div class="mt-6">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{$category->name}}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <!-- Title -->
                    <div class="mt-6">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text"
                            name="title" :value="old('title')"  autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Content -->
                     <div class="mt-6">
                         <x-input-label for="content" :value="__('Content')" />
                         <x-input-textarea id="content" class="block mt-1 w-full" name="content" >
                             {{ old('content') }}
                         </x-input-textarea>
                         <x-input-error :messages="$errors->get('content')" class="mt-2" />
                     </div>

                    <!-- Published At -->
                    <div class="mt-6">
                        <x-input-label for="published_at" :value="__('Published At')" />
                        <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local"
                            name="published_at" :value="old('published_at')"  autofocus />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                     <x-primary-button class="mt-6">
                         Submit 
                     </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>