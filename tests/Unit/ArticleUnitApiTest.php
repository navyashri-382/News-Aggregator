<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleUnitApiTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_fetch_paginated_artcles(){
        Article::create([
            'title' => 'Test Article',
            'content' => 'This is a test article content.',
            'author' => 'John Doe',
            'source' => 'Test Source',
            'category' => 'Test Category',
            'published_at' => now(),
            'image_url' => 'http://example.com/image.jpg',
            'url' => 'http://example.com/test-article',
        ]);
    
        $response = $this->get('/api/articles');
    
        $response->assertStatus(200)->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => ['id', 'title', 'content', 'author', 'source', 'category', 'published_at', 'image_url', 'url']
            ],
            'first_page_url', 'last_page', 'last_page_url', 'per_page', 'total',
        ]);
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
        ]);
    }
    
  
    public function  test_fetch_single_article() {
        $article = Article::create([
            'title' => 'Yankees blow 5-run lead with epic defensive meltdown as Dodgers rally to clinch World Series - The Associated Press',
            'content' => 'Just when it appeared Aaron Judge and the New York Yankees were right back in this World Series, they all but gave away the trophy. An epic meltdown of defensive miscues, beginning with Judge’s embarrassing error in center field, helped the Los Angeles Dod [...]',
            'author' => 'MIKE FITZPATRICK',
            'source' => 'NewsAPI Org',
            'category' => null,
            'published_at' => '2024-10-31 09:38:00',
            'image_url' => 'https://dims.apnews.com/dims4/default/32d4649/2147483647/strip/true/crop/4190x2357+0+218/resize/1440x810!/quality/90/?url=https%3A%2F%2Fassets.apnews.com%2F4f%2F24%2Ff2075171983be7258ed948b21d1b%2F7f46d2f9c32e408581ff815ff344fc82',
            'url' => 'https://apnews.com/article/world-series-yankees-errors-ff3ca215e6064c1983e4cce4f41a97e0',
        ]);

        $response = $this->get('/api/articles/' . $article->id);

        $response->assertStatus(200)
        ->assertJson([
            'id' => $article->id,
            'title' => 'Yankees blow 5-run lead with epic defensive meltdown as Dodgers rally to clinch World Series - The Associated Press',
            'content' => 'Just when it appeared Aaron Judge and the New York Yankees were right back in this World Series, they all but gave away the trophy. An epic meltdown of defensive miscues, beginning with Judge’s embarrassing error in center field, helped the Los Angeles Dod [...]',
            'author' => 'MIKE FITZPATRICK',
            'source' => 'NewsAPI Org',
            'category' => null,
            'published_at' => '2024-10-31 09:38:00',
            'image_url' => 'https://dims.apnews.com/dims4/default/32d4649/2147483647/strip/true/crop/4190x2357+0+218/resize/1440x810!/quality/90/?url=https%3A%2F%2Fassets.apnews.com%2F4f%2F24%2Ff2075171983be7258ed948b21d1b%2F7f46d2f9c32e408581ff815ff344fc82',
            'url' => 'https://apnews.com/article/world-series-yankees-errors-ff3ca215e6064c1983e4cce4f41a97e0',
        ]);
    }

    public function test_fetch_nonexistent_article(){
    $response = $this->getJson('/api/articles/999'); 

    $response->assertStatus(404)->assertJson(['message' => 'Article not found.']); 
}
}

