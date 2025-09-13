<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex">
                    <div class="flex-1 pr-8">
                        <h1 class="text-5xl font-bold mb-2">{{ $user->name }}</h1>
                        <div class="mt-8">
                            @forelse ($posts as $post)
                                <x-post-item :post="$post" />
                            @empty
                                <p class="text-center text-gray-400 py-16">No posts found.</p>
                            @endforelse
                        </div>
                    </div>
                    <x-follow-ctr :user="$user">
                        <x-user-avatar :user="$user" size="w-24 h-24" />
                        <h3 class="mt-2">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600"> 
                            <span x-text="followersCount"></span>
                            Followers
                        </p>
                        <p class="mt-2">
                            {{ $user->bio ?? 'This user has no bio yet.' }}
                        </p>
                            @if(auth()->user() && auth()->user()->id !== $user->id)
                                <button 
                                    class="mt-4 px-4 py-2 text-white rounded-full"
                                    x-text="following ? 'Unfollow' : 'Follow'"
                                    :class="following ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'"
                                    @click="follow()"
                                >
                                </button>
                            @endif
                    </x-follow-ctr>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>