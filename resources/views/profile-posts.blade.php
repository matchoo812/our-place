<x-profile :profileData="$profileData" doctitle="{{$profileData['username']}}'s Profile">
      
    @foreach ($posts as $post)
    <x-post :post="$post" hideAuthor />
    @endforeach
  
  </div>
</x-profile>