<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/articles",
 *     tags={"Articles"},
 *     summary="Fetch a paginated list of articles with filters",
 *     description="Retrieve articles with optional filters for keyword, date, category, and source.",
 *     @OA\Parameter(
 *         name="keyword",
 *         in="query",
 *         description="Search for articles with a keyword in the title or content",
 *         required=false,
 *         @OA\Schema(type="string", example="‘Naked and Afraid’ star Sarah Danser dies in crash at age 34 - New York Post")
 *     ),
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="Filter articles by a specific publication date",
 *         required=false,
 *         @OA\Schema(type="string", format="date", example="2024-10-25 08:07:00")
 *     ),
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         description="Filter articles by category",
 *         required=false,
 *         @OA\Schema(type="string", example="")
 *     ),
 *     @OA\Parameter(
 *         name="source",
 *         in="query",
 *         description="Filter articles by source",
 *         required=false,
 *         @OA\Schema(type="string", example="NewsAPI Org")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="A paginated list of articles",
 *         @OA\JsonContent(
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="title", type="string", example="Latest in Tech"),
 *                     @OA\Property(property="content", type="string", example="An overview of technology trends..."),
 *                     @OA\Property(property="author", type="string", example="Jane Doe"),
 *                     @OA\Property(property="source", type="string", example="BBC News"),
 *                     @OA\Property(property="category", type="string", example="Technology"),
 *                     @OA\Property(property="published_at", type="string", format="date-time", example="2024-10-25T08:07:00Z"),
 *                     @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
 *                     @OA\Property(property="url", type="string", example="https://example.com/article")
 *                 )
 *             ),
 *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/articles?page=1"),
 *             @OA\Property(property="last_page", type="integer", example=5),
 *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/articles?page=5"),
 *             @OA\Property(property="per_page", type="integer", example=10),
 *             @OA\Property(property="total", type="integer", example=50)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request parameters",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid filter values provided")
 *         )
 *     )
 * )
 */
    public function index(Request $request){
        $query = Article::query();
        if ($request->has('keyword')) {
            $query->where('title', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('content', 'LIKE', '%' . $request->keyword . '%');
        }
        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        $articles = $query->paginate(10); 

        return response()->json($articles);
    }

    /**
 * @OA\Get(
 *     path="/api/articles/{id}",
 *     tags={"Articles"},
 *     summary="Fetch a specific article by ID",
 *     description="Retrieve details of a particular article using its unique ID.",
 *     operationId="getArticle",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="The ID of the article to retrieve",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article details retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Article Title"),
 *             @OA\Property(property="content", type="string", example="This is the content of the article."),
 *             @OA\Property(property="author", type="string", example="Author Name"),
 *             @OA\Property(property="source", type="string", example="News Source"),
 *             @OA\Property(property="category", type="string", example="Technology"),
 *             @OA\Property(property="published_at", type="string", format="date-time", example="2024-10-25T08:07:00"),
 *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
 *             @OA\Property(property="url", type="string", example="https://example.com/article"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Article not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Article not found")
 *         )
 *     ),
 *     security={{"apiAuth": {}}}
 * )
 */

    public function show($id): JsonResponse {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found.'], 404); // Add a period here
        }

        return response()->json($article);
    }

    

}
