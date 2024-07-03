<x-profile :profileData="$profileData">
      
    @foreach ($followers as $follower)
    <a href="/profile/{{$follower->followingUser->username}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$follower->followingUser->avatar}}" />
      {{$follower->followingUser->username}}
    </a>
    @endforeach
  
  </div>
</x-profile>