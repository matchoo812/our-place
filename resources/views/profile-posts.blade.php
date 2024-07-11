<x-profile :profileData="$profileData" doctitle="{{$profileData['username']}}'s Profile">
  @include('profile-posts-only')
  
</x-profile>