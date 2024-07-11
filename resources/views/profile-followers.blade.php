<x-profile :profileData="$profileData" doctitle="{{$profileData['username']}}'s Followers">
  @include('profile-followers-only')
</x-profile>