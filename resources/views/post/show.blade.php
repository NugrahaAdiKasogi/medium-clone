<x-app-layout>
    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                {{-- Header --}}
                <h1 class="text-3xl !important mb-4">{{ $post->title }}</h1>
                <div class="flex gap-4">
                    <x-user-avatar :user="$post->user" />
                    {{-- user avatar section --}}
                    <div class="flex flex-col justify-center">
                        <x-follow-ctr :user="$post->user" class="flex gap-4 ">
                            <a href="{{route('profile.show', $post->user)}}" 
                                class="font-bold hover:underline">
                                {{ $post->user->name }}
                            </a>
                            @auth
                            <span class="text-sm text-gray-400">•</span>
                            <button
                                x-text="following ? 'Unfollow' : 'Follow'"
                                :class="following ? 'text-red-500' : 'text-green-500'"
                                @click="follow()"
                            >
                            </button>
                            @endauth
                        </x-follow-ctr>
                        <div class="flex items-center gap-2">
                            <p class="text-sm text-gray-400">{{ $post->readTime() }} min read</p>
                            <span class="text-sm text-gray-400">•</span>
                            <p class="text-sm text-gray-400">{{ $post->formatted_published_at }}</p>

                        </div>
                        {{-- Clap Section --}}
                        <div class="mt-2">
                            <x-clap-button :post="$post"/>
                        </div>
                    </div>
                </div>

                @if (Auth::id() === $post->user_id)
                    <div class="mt-4 py-4 border-t border-gray-200 w-full flex gap-2">
                        <x-primary-button href="{{route('post.edit', $post->slug)}}">
                            Edit Post
                        </x-primary-button>
                        <form method="POST" action="{{route('post.destroy', $post)}}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>
                                Delete Post
                            </x-danger-button>
                        </form>
                    </div>
                @endif
                {{-- Body --}}
                <div class="mt-8">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"
                        class="w-full h-full object-cover mt-4 rounded-lg">
                    <div class="mt-4 whitespace-pre-line text-justify">
                        {{ $post->content }}
                    </div>
                    <div class="mt-8">
                        <span class="px-4 py-2 bg-gray-200 rounded-full">
                            {{$post->category->name}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>