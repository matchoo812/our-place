<x-profile :profileData="$profileData" doctitle="Who {{$profileData['username']}} Follows">
      
    @foreach ($following as $follow)
    <a href="/profile/{{$follow->followedUser->username}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$follow->followedUser->avatar}}" />
      {{$follow->followedUser->username}}
    </a>
    @endforeach
  
  </div>
</x-profile>