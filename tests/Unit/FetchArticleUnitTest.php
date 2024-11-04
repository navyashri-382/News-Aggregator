<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchArticleUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_articles_src()
    {
        Http::fake([
            'newsapi.org/*' => Http::response(['articles' => [
                ['title' => 'NewsAPI Article', 'url' => 'http://newsapi.com', 'publishedAt' => now()]
            ]], 200),
            'guardianapis.com/*' => Http::response(['response' => ['results' => [
                ['webTitle' => 'Guardian Article', 'webUrl' => 'http://guardian.com']
            ]]], 200),
            'feeds.bbci.co.uk/*' => Http::response('<?xml version="1.0"?><rss><channel><item><title>BBC Article</title><link>http://bbc.com</link></item></channel></rss>', 200),
        ]);
    
        Artisan::call('fetch:articles');
    
        // Assertions
        $this->assertDatabaseHas('articles', [
            'title' => 'NewsAPI Article',
            'url' => 'http://newsapi.com',
        ]);
        $this->assertDatabaseHas('articles', [
            'title' => 'Guardian Article',
            'url' => 'http://guardian.com',
        ]);
        $this->assertDatabaseHas('articles', [
            'title' => 'BBC Article',
            'url' => 'http://bbc.com',
        ]);
    }
    
    public function test_fetch_article_fail()
    {
        Http::fake([
            'newsapi.org/*' => Http::response([], 500),
        ]);
    
        $exitCode = Artisan::call('fetch:articles');
        $this->assertEquals(1, $exitCode);
    }
    
}
