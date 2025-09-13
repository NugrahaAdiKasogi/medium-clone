@props(['user'])

<div {{$attributes}} x-data="{
        following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
        followersCount: {{ $user->followers()->count() }},
        follow() {
            axios.post('/follow/{{{$user->username}}}')
            .then(response => {
                this.following = !this.following;
                this.followersCount = response.data.followersCount;
            }).catch(error => {
                console.error(error);
            });
        }, 
    }" class="w-[320px] border-l px-8">
    {{ $slot }}
</div>