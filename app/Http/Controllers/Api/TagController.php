<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Response;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json(
            TagResource::collection($tags)
        );
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->only('name'));
        return response()->json(new TagResource($tag), Response::HTTP_CREATED);
    }


    // Retrieve a single tag
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json(new TagResource($tag));
    }

    // Update a tag
    public function update(UpdateTagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->update($request->only('name'));

        return response()->json($tag);
    }

    // Delete a tag
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
