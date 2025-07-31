<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\DestroyLikeRequest;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Like a post.
     *
     * @param StoreLikeRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function store(StoreLikeRequest $request, Post $post): JsonResponse
    {
        try {
            $like = Like::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);

            return response()->json([
                'status' => 'success',
                'like' => $like
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unlike a post.
     *
     * @param DestroyLikeRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(DestroyLikeRequest $request, Post $post): JsonResponse
    {
        try {
            $like = Like::where('user_id', Auth::id())->where('post_id', $post->id)->first();
            if (!$like) {
                return response()->json([
                    'status' => 'error',
                    'errors' => 'Like not found'
                ], 404);
            }

            $this->authorize('delete', $like);

            $like->delete();

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
}