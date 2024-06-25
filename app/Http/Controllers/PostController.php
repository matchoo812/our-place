<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm()
    {
        return view('create-post');
    }

    public function showEditForm(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }

    public function storeNewPost(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        return redirect("/post/{$newPost->id}")->with('success', 'New Post Created.');
    }

    public function showSinglePost(Post $post)
    {
        // allow markdown interpretation on the body of the post; use strip_tags fn to choose which tags are allowed
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h2><h3><br><hr>');
        return view('single-post', ['post' => $post]);
    }

    public function updatePost(Post $post, Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);
        return back()->with('success', 'Post successfully updated.');
    }

    public function delete(Post $post)
    {
        // if (auth()->user()->cannot('delete', $post)) {
        //     return 'You are not authorized to delete this post';
        // }

        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted.');
    }
}
