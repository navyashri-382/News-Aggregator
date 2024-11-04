<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;
    

    public function test_fetch_artcls_filters(){
        Article::factory()->create([
            'title' => 'Nobody here feels ignored: Harris and Trump barnstorm Wisconsin - POLITICO',
            'content' => 'Wisconsin was the closest of the three Blue Wall states in 2020, but this year, both campaigns have spent more of their time and money elsewhere..',
            'author' => 'Irie Sentner, Lisa Kashinsky, Jessica Piper',
            'source' => 'NewsAPI Org',
            'category' => null,
            'published_at' => now(),
            'image_url' => 'https://static.politico.com/6b/86/293c8e614ecebbea27aa2fa4eac0/useuntil11-30-2024-kamala-harris-wisconsin-012.jpg',
            'url' => 'https://www.politico.com/news/2024/10/30/harris-trump-wisconsin-campaign-2024-00186427',
        ]);
        Article::factory()->create([
            'title' => 'Nobody here feels ignored: Harris and Trump barnstorm Wisconsin - POLITICO',
            'content' => '',
            'author' => '',
            'source' => 'The Guardian',
            'category' => null,
            'published_at' => now(),
            'image_url' => '',
            'url' => 'https://www.theguardian.com/australia-news/live/2024/nov/01/australia-news-live-qld-election-cabinet-sworn-in-business-cost-of-living-house-prices-power-bills',
        ]);

        $response = $this->get('/api/articles?keyword=Nobody');
        $response->assertStatus(200)->assertJsonFragment(['title' => 'Nobody here feels ignored: Harris and Trump barnstorm Wisconsin - POLITICO']);
    }

    public function test_fetch_paginatd_artcle()
    {
        for ($i = 1; $i <= 10; $i++) {
            Article::create([
                'title' => "Nobody here feels ignored: Harris and Trump barnstorm Wisconsin - POLITICO {$i}",
                'content' => "Wisconsin was the closest of the three Blue Wall states in 2020, but this year, both campaigns have spent more of their time and money elsewhere.. {$i}.",
                'author' => "Irie Sentner, Lisa Kashinsky, Jessica Piper {$i}",
                'source' => 'NewsAPI Org',
                'category' => null,
                'published_at' => now(),
                'image_url' => 'https://static.politico.com/6b/86/293c8e614ecebbea27aa2fa4eac0/useuntil11-30-2024-kamala-harris-wisconsin-012.jpg',
                'url' => 'https://www.politico.com/news/2024/10/30/harris-trump-wisconsin-campaign-2024-00186427' . $i,
            ]);
        }
        $response = $this->getJson('/api/articles');
        $response->assertOk();
        $response->assertJsonStructure(['current_page', 'data'])->assertJsonCount(10, 'data');
    }

  
    public function test_fetch_artcle_by_id(){
    
        $article = Article::create([
            'title' => 'Nobody here feels ignored: Harris and Trump barnstorm Wisconsin - POLITICO',
            'content' => 'Wisconsin was the closest of the three Blue Wall states in 2020, but this year, both campaigns have spent more of their time and money elsewhere..',
            'author' => 'Irie Sentner, Lisa Kashinsky, Jessica Piper',
            'source' => 'NewsAPI Org',
            'category' => null,
            'published_at' => now(), 
            'image_url' => 'https://static.politico.com/6b/86/293c8e614ecebbea27aa2fa4eac0/useuntil11-30-2024-kamala-harris-wisconsin-012.jpg',
            'url' => 'https://www.politico.com/news/2024/10/30/harris-trump-wisconsin-campaign-2024-00186427'
        ]);
        $response = $this->getJson('/api/articles/' . $article->id);

   
        $response->assertOk()->assertJson([
            'id' => $article->id,
            'title' => 'Nobody here feels ignored: Harris and Trump barnstorm Wisconsin - POLITICO',
            'content' => 'Wisconsin was the closest of the three Blue Wall states in 2020, but this year, both campaigns have spent more of their time and money elsewhere..',
            'author' => 'Irie Sentner, Lisa Kashinsky, Jessica Piper',
            'source' => 'NewsAPI Org',
            'category' => null,
            'published_at' => $article->published_at->format('Y-m-d H:i:s'), // Match the returned format
            'image_url' => 'https://static.politico.com/6b/86/293c8e614ecebbea27aa2fa4eac0/useuntil11-30-2024-kamala-harris-wisconsin-012.jpg',
            'url' => 'https://www.politico.com/news/2024/10/30/harris-trump-wisconsin-campaign-2024-00186427',
        ]);
    }


    
}
