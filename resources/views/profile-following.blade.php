<x-profile :profileData="$profileData" doctitle="Who {{$profileData['username']}} Follows">
  @include('profile-following-only')
</x-profile>