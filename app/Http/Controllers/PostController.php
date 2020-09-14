<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(): JsonResource
    {
        // TODO: Get approved posts only (use best practices).
        // TODO: Refactor.
        $posts = Post::all();

        return PostResource::collection($posts); // TODO: Add posts count to response.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Refactor.
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        if ($validator->passes()) {
            $post = auth()->user()->posts()->create($validator->validated());
            // TODO: Perform text moderation, approve/reject the post, send a notification to the user.
            return response()->json(['message' => 'Success.'], 202);
        } else {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Post $post): JsonResource
    {
        // TODO: Refactor (N+1).
        return new PostResource($post);
    }
}
