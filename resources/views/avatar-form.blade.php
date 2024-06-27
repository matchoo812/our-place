<x-layout>
  <div class="container container--narrow py-md-5">
    <h2 class="text-center mb-3">Upload a new Avatar</h2>
    {{-- set encoding type attribute to submit file --}}
    <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
    @csrf 
    <div class="mb-3">
      <input type="file" name="avatar" required>
      <label for="avatar" class="d-block mt-1">
        Images must be no larger than 3mb.
      </label>
      @error('avatar')
      <p class="alert small alert-danger shadow-sm">{{$message}}</p>
      @enderror
    </div>
    <button class="btn btn-primary">Save</button>
    </form>
  </div>
</x-layout>