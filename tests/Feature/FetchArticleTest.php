<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_articles_fetch_api()
    {
        // Manually create 3 articles
        Article::create([
            'title' => 'Jets vs. Texans odds, prediction, time, spread, line: Thursday Night Football picks by NFL model on 13-5 roll - CBS Sports',
            'content' => "SportsLine's model simulated Houston vs. New York on Thursday Night Football 10,000 times",
            'author' => NULL,
            'source' => 'NewsAPI Org',
            'category' => NULL,
            'published_at' => now(),
            'image_url' => 'https://sportshub.cbsistatic.com/i/r/2024/09/11/1626dad2-3813-417f-9032-5b642b3b023d/thumbnail/1200x675/8eaa735e0d043b2a32f5265716a9a97a/aaron-rodgers-cbs-2.jpg',
            'url' => 'https://www.cbssports.com/nfl/news/jets-vs-texans-odds-prediction-time-spread-line-thursday-night-football-picks-from-nfl-model-on-13-5-roll/',
        ]);
    
        Article::create([
            'title' => 'Tube strikes by RMT staff called off after talks',
            'content' => NULL,
            'author' => NULL,
            'source' => 'BBC News',
            'category' => NULL,
            'published_at' => now(),
            'image_url' => NULL,
            'url' => 'https://www.bbc.com/news/articles/c78d9nn17z8o',
        ]);
    
        Article::create([
            'title' => 'Disposable vapes ban could push some users back to smoking, ministers told',
            'content' => 'Content for sample article 3.',
            'author' => NULL,
            'source' => 'The Guardian',
            'category' => NULL,
            'published_at' => now(),
            'image_url' => NULL,
            'url' => 'https://www.theguardian.com/society/2024/nov/01/disposable-vapes-ban-could-push-users-back-smoking-defra',
        ]);
    
        // Make a request to the API
        $response = $this->getJson('/api/articles');
        
        // Assert the response
        $response->assertOk()
                 ->assertJsonStructure([
                     'current_page',
                     'data' => [
                         ['id', 'title', 'content', 'author', 'source', 'published_at', 'image_url', 'url']
                     ]
                 ]);
    }
    

    public function test_fetch_article_by_id()
    {
        // Create a sample article
        $article = Article::create([
            'title' => 'Jets vs. Texans odds, prediction, time, spread, line: Thursday Night Football picks by NFL model on 13-5 roll - CBS Sports',
            'content' => "SportsLine's model simulated Houston vs. New York on Thursday Night Football 10,000 times",
            'author' => NULL,
            'source' => 'NewsAPI Org',
            'category' => NULL,
            'published_at' => now(),
            'image_url' => 'https://sportshub.cbsistatic.com/i/r/2024/09/11/1626dad2-3813-417f-9032-5b642b3b023d/thumbnail/1200x675/8eaa735e0d043b2a32f5265716a9a97a/aaron-rodgers-cbs-2.jpg',
            'url' => 'https://www.cbssports.com/nfl/news/jets-vs-texans-odds-prediction-time-spread-line-thursday-night-football-picks-from-nfl-model-on-13-5-roll/',
        ]);
    
        // Fetch the article by ID
        $response = $this->getJson('/api/articles/' . $article->id);
    
        // Assert the response
        $response->assertOk()->assertJson([
            'id' => $article->id,
            'title' => 'Jets vs. Texans odds, prediction, time, spread, line: Thursday Night Football picks by NFL model on 13-5 roll - CBS Sports',
            'content' => "SportsLine's model simulated Houston vs. New York on Thursday Night Football 10,000 times",
            'author' => NULL,
            'source' => 'NewsAPI Org',
            'category' => NULL,
            'published_at' => now(),
            'image_url' => 'https://sportshub.cbsistatic.com/i/r/2024/09/11/1626dad2-3813-417f-9032-5b642b3b023d/thumbnail/1200x675/8eaa735e0d043b2a32f5265716a9a97a/aaron-rodgers-cbs-2.jpg',
            'url' => 'https://www.cbssports.com/nfl/news/jets-vs-texans-odds-prediction-time-spread-line-thursday-night-football-picks-from-nfl-model-on-13-5-roll/',

        ]);
    }
}
?>
