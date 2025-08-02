<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param StoreCommentRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        try {
            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'status' => 'success',
                'comment' => $comment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified comment in storage.
     *
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        try {
            $comment->update($request->only(['comment']));

            return response()->json([
                'status' => 'success',
                'comment' => $comment->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified comment.
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        try {
            $comment->delete();

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
     * Get all comments for a post.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function index(Post $post): JsonResponse
    {
        try {
            $comments = Comment::where('post_id', $post->id)
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->paginate(10);

            return response()->json([
                'status' => 'success',
                'current_page' => $comments->currentPage(),
                'data' => $comments->items(),
                'last_page' => $comments->lastPage(),
                'total' => $comments->total()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}