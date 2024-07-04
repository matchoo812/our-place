<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    private function getProfileData($user)
    {
        $isFollowing = 0;

        if (auth()->check()) {
            $isFollowing = Follow::where([['user_id', '=',  auth()->user()->id], ['followed_user', '=', $user->id]])->count();
        }

        View::share('profileData', ['username' => $user->username, 'avatar' => $user->avatar, 'postCount' => $user->posts()->count(), 'isFollowing' => $isFollowing, 'followerCount' => $user->followers()->count(), 'followingCount' => $user->following()->count()]);
    }
    public function profile(User $user)
    {
        $this->getProfileData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }
    public function profileFollowers(User $user)
    {
        $this->getProfileData($user);
        return view('profile-followers', ['followers' => $user->followers()->get()]);
    }
    public function profileFollowing(User $user)
    {
        $this->getProfileData($user);
        return view('profile-following', ['following' => $user->following()->get()]);
    }
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:9', 'confirmed']
        ]);

        $user = User::create($incomingFields);
        auth()->login($user);

        return redirect('/')->with('success', 'Thanks for registering!');
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/')->with('failure', 'Invalid login credentials');
        }
    }

    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->get()]);
        } else {
            return view('homepage');
        }
    }

    public function showAvatarForm()
    {
        return view('avatar-form');
    }

    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        $user = auth()->user();

        $filename = $user->id . "-" . $user->username . "-" . uniqid() . ".jpg";

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('avatar'));
        $imgData = $image->cover(150, 150)->toJpeg();
        Storage::put("public/avatars/{$filename}", $imgData);

        // wait for new avatar to be saved in db before deleting old one
        $oldAvatar = $user->avatar;
        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return redirect("/profile/{$user->username}")->with('success', 'Avatar successfully changed.');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out.');
    }
}
