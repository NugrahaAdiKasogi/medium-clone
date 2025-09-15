<x-app-layout>
    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">
                    {{-- post/index.blade.php --}}
                    @if (Route::currentRouteName() === 'myPosts' || Route::currentRouteName() === 'myPosts.byCategory')
                        {{-- Navbar khusus My Posts --}}
                        <x-my-post-category-nav :categories="$categories" />
                    @else
                        {{-- Navbar khusus Dashboard (Following) --}}
                        <x-category-tabs :categories="$categories" />
                    @endif
                </div>
            </div>
            <div class="text-gray-900 mt-8">
                <div class="mb-4">
                    @forelse ($posts as $post)
                        <x-post-item :post="$post" />
                    @empty
                        <p class="text-center text-gray-400 py-16">No posts found.</p>
                    @endforelse
                </div>
                {{$posts->links('vendor.pagination.simple-tailwind')}}
            </div>
        </div>
    </div>
</x-app-layout>