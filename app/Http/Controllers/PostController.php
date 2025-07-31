<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created post in storage.
     *
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'text' => $request->text,
            ]);

            return response()->json([
                'status' => 'success',
                'post' => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified post in storage.
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        try {
            $this->authorize('update', $post);

            $post->update($request->only(['title', 'text']));

            return response()->json([
                'status' => 'success',
                'post' => $post->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified post.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        try {
            $this->authorize('delete', $post);

            $post->delete();

            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all posts.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $posts = Post::withCount(['comments', 'likes'])->paginate(10);

            return response()->json([
                'status' => 'success',
                'current_page' => $posts->currentPage(),
                'data' => $posts->items(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all posts for the authenticated user.
     *
     * @return JsonResponse
     */
    public function userPosts(): JsonResponse
    {
        try {
            $posts = Post::where('user_id', Auth::id())->withCount(['comments', 'likes'])->paginate(10);

            return response()->json([
                'status' => 'success',
                'current_page' => $posts->currentPage(),
                'data' => $posts->items(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single post by ID.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        try {
            $post->loadCount(['comments', 'likes']);

            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}