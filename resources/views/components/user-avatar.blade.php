@props(['user','size' => 'w-12 h-12'])    

@if ($user->image)
    <img src="{{ $user->imageUrl() }}" alt="{{$user->name}}" class="{{$size}} rounded-full object-cover">
@else
    <div class="{{$size}} rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhtMRbtowke9ZnnGtyYJmIuJaB2Q1y5I-3IA&s" alt="">
    </div>
@endif