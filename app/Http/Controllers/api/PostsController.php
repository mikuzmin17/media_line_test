<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function getPosts(Post $post=null)
    {
        if ($post == null) {
            return response()->json(Post::orderByDesc('created_at')->limit(15)->get());
        } else {
            try {
                return response()->json($post);
            } catch (\Throwable $e) {
                $message = 'Мы не нашли этот пост';
                return response()->json($message, 400);
            }
        }
    }
}
