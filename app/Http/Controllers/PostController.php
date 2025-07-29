<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
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
                'message' => 'Post created successfully',
                'post' => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
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
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized to update this post'
                ], 403);
            }

            $post->update($request->only(['title', 'text']));

            return response()->json([
                'message' => 'Post updated successfully',
                'post' => $post->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update post',
                'error' => $e->getMessage()
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
            $posts = Post::paginate(10);

            return response()->json([
                'message' => 'Posts retrieved successfully',
                'current_page' => $posts->currentPage(),
                'data' => $posts->items(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve posts',
                'error' => $e->getMessage()
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
            $posts = Post::where('user_id', Auth::id())->paginate(10);

            return response()->json([
                'message' => 'User posts retrieved successfully',
                'current_page' => $posts->currentPage(),
                'data' => $posts->items(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve user posts',
                'error' => $e->getMessage()
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
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'message' => 'Post deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}