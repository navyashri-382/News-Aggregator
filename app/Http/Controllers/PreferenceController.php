<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preference; 
use App\Models\Article; 
use App\Models\User; 

class PreferenceController extends Controller
{

      /**
     * @OA\Post(
     *     path="/api/preferences",
     *     tags={"Preferences"},
     *     summary="Set user preferences",
     *     description="Allows users to set their preferred news sources, categories, and authors.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example="1"),
     *             @OA\Property(property="preferred_source", type="string", example="NewsAPI Org"),
     *             @OA\Property(property="preferred_category", type="string", example=""),
     *             @OA\Property(property="preferred_author", type="string", example="Nika Shakhnazarova")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Preferences updated successfully"),
     *             @OA\Property(property="user_preferences", type="object")
     *         )
     *     ),
     *     security={{"apiAuth": {}}}
     * )
     */
   // Set user preferences
   public function setPreferences(Request $request)
   {
       $request->validate([
           'user_id' => 'required|integer|exists:users,id',
           'preferred_source' => 'nullable|string',
           'preferred_category' => 'nullable|string',
           'preferred_author' => 'nullable|string',
       ]);
   
       // Check if the user exists
       $user = User::find($request->user_id);
       if (!$user) {
           return response()->json(['message' => 'User not found.'], 404);
       }
   
       $preferences = Preference::updateOrCreate(
           ['user_id' => $request->user_id],
           $request->only(['preferred_source', 'preferred_category', 'preferred_author'])
       );
   
       return response()->json(['message' => 'Preferences updated successfully.', 'user_preferences' => $preferences]);
   }

  /**
 * @OA\Get(
 *     path="/api/preferences",
 *     tags={"Preferences"},
 *     summary="Retrieve preferences for a specific user",
 *     description="Fetches the preferences for the specified user, including preferred news sources, categories, and authors.",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         required=true,
 *         description="ID of the user to retrieve preferences for",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Preferences retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="preferences", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="preferred_source", type="string", example="NewsAPI Org"),
 *                 @OA\Property(property="preferred_category", type="string", example=""),
 *                 @OA\Property(property="preferred_author", type="string", example="Nika Shakhnazarova"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-26T12:10:38.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-26T12:22:59.000000Z")
 *             )
 *         )
 *     ),
 *     security={{"apiAuth": {}}}
 * )
 */
   // Get user preferences
   public function getPreferences(Request $request){
       $request->validate([
           'user_id' => 'required|integer|exists:users,id',
       ]);
   
       $preferences = Preference::where('user_id', $request->user_id)->first();
   
       return response()->json(['preferences' => $preferences]);
   }
   
/**
 * @OA\Get(
 *     path="/api/personalized-feed",
 *     tags={"Preferences"},
 *     summary="Fetch a personalized news feed",
 *     description="Retrieve a personalized news feed based on the user's preferences for source, category, and author.",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the user to retrieve the personalized feed for"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Personalized feed retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="title", type="string", example="‘Naked and Afraid’ star Sarah Danser dies in crash at age 34 - New York Post"),
 *                     @OA\Property(property="content", type="string", example="The reality TV star appeared on the survival series in 2017."),
 *                     @OA\Property(property="author", type="string", example="Nika Shakhnazarova"),
 *                     @OA\Property(property="source", type="string", example="NewsAPI Org"),
 *                     @OA\Property(property="category", type="string", nullable=true, example=null),
 *                     @OA\Property(property="published_at", type="string", format="date-time", example="2024-10-25 08:07:00"),
 *                     @OA\Property(property="image_url", type="string", example="https://nypost.com/wp-content/uploads/sites/2/2024/10/92346424.jpg?quality=75&strip=all&w=1024"),
 *                     @OA\Property(property="url", type="string", example="https://nypost.com/2024/10/25/entertainment/naked-and-afraid-star-dies-in-crash-at-age-34/"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-26T09:21:35.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-26T09:21:35.000000Z")
 *                 )
 *             ),
 *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/personalized-feed?page=1"),
 *             @OA\Property(property="from", type="integer", example=1),
 *             @OA\Property(property="last_page", type="integer", example=1),
 *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/personalized-feed?page=1"),
 *             @OA\Property(
 *                 property="links",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="url", type="string", nullable=true, example=null),
 *                     @OA\Property(property="label", type="string", example="&laquo; Previous"),
 *                     @OA\Property(property="active", type="boolean", example=false)
 *                 )
 *             ),
 *             @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
 *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/personalized-feed"),
 *             @OA\Property(property="per_page", type="integer", example=10),
 *             @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
 *             @OA\Property(property="to", type="integer", example=1),
 *             @OA\Property(property="total", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No preferences set for the user",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="No preferences set.")
 *         )
 *     ),
 *     security={{"apiAuth": {}}}
 * )
 */

   public function personalizedFeed(Request $request){
        $preferences = Preference::where('user_id', $request->user_id)->first();

        if (!$preferences) {
            return response()->json(['message' => 'No preferences set.'], 404);
        }

        $articles = Article::query()
            ->when($preferences->preferred_source, function ($query) use ($preferences) {
                $query->where('source', $preferences->preferred_source);
            })
            ->when($preferences->preferred_category, function ($query) use ($preferences) {
                $query->where('category', $preferences->preferred_category);
            })
            ->when($preferences->preferred_author, function ($query) use ($preferences) {
                $query->where('author', $preferences->preferred_author);
            })
            ->paginate(10); 

       return response()->json($articles);
   }
}
