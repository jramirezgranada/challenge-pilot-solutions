<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articles,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Article::class);

        $articles = $this->articles->paginate((int) $request->integer('per_page', 15));

        return response()->json([
            'data' => ArticleResource::collection($articles->items()),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articles->create(
            $request->user(),
            $request->safe()->except('category_ids'),
            $request->validated('category_ids'),
        );

        return response()->json([
            'message' => 'Article created successfully.',
            'data' => ArticleResource::make($article),
        ], 201);
    }

    public function show(Article $article): JsonResponse
    {
        $this->authorize('view', $article);

        return response()->json([
            'data' => ArticleResource::make($article->load(['author', 'categories'])),
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $article = $this->articles->update(
            $article,
            $request->user(),
            $request->safe()->except('category_ids'),
            $request->validated('category_ids'),
        );

        return response()->json([
            'message' => 'Article updated successfully.',
            'data' => ArticleResource::make($article),
        ]);
    }

    public function destroy(Article $article): JsonResponse
    {
        $this->authorize('delete', $article);

        $this->articles->delete($article);

        return response()->json([
            'message' => 'Article deleted successfully.',
        ]);
    }
}
