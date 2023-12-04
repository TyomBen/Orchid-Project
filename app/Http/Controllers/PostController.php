<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store (CreatePostRequest $request)
    {
        Post::create([
            'title' => $request->title,
            'user_id' => Auth::user()->id,
            'content' => $request->content
        ]);
        redirect ()->back();
    }
}
