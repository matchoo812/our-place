<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
        // users cannot follow themselves
        if ($user->id == auth()->user()->id) {
            return back()->with('failure', 'You cannot follow yourself.');
        }
        // users cannot follow someone they're already following
        $isFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followed_user', '=', $user->id]])->count();
        if ($isFollowing) {
            return back()->with('failure', "You are already following {$user->username}.");
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followed_user = $user->id;
        $newFollow->save();

        return back()->with('success', "You are now following {$user->username}.");
    }

    public function removeFollow(User $user)
    {
        Follow::where([['user_id', '=', auth()->user()->id], ['followed_user', '=', $user->id]])->delete();
        return back()->with('success', "You are no longer following {$user->username}.");
    }
}
