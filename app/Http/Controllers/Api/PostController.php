<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        $posts = $user->posts()->with('tags')->orderBy('pinned', 'desc')->get();

        return response()->json(PostResource::collection($posts));
    }

    public function store(StorePostRequest $request)
    {
        $user = Auth::guard('api')->user();
        $post = $user->posts()->create($request->validated());

        $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');
        $coverImageName = ($coverImagePath);
        $post->cover_image = $coverImageName;
        $post->save();
        $post->tags()->sync($request->tags);

        return response()->json(new PostResource($post), 201);
    }

    public function show($id)
    {
        $user = Auth::guard('api')->user();
        $post = $user->posts()->with('tags')->findOrFail($id);

        return response()->json(new PostResource($post));
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $user = Auth::guard('api')->user();

        $post = $user->posts()->findOrFail($id);
        $post->update($request->validated());

        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');
            $coverImageName = ($coverImagePath);
            $post->cover_image = $coverImageName;
            $post->save();
        }

        $post->tags()->sync($request->tags);

        return response()->json(new PostResource($post), 201);
    }

    public function destroy($id)
    {
        $user = Auth::guard('api')->user();

        $post = $user->posts()->findOrFail($id);
        $post->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function viewDeletedPosts()
    {
        $user = Auth::guard('api')->user();

        $deletedPosts = $user->posts()->onlyTrashed()->get();
        return response()->json(PostResource::collection($deletedPosts));
    }

    public function restoreDeletedPost($id)
    {
        $user = Auth::guard('api')->user();
        $post = $user->posts()->onlyTrashed()->findOrFail($id);

        $post->restore();

        return response()->json(['message' => 'Post restored successfully',new PostResource($post)]);
    }

}
