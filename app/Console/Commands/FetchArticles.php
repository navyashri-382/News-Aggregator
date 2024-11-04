<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Article;
use Log;


class FetchArticles extends Command
{
    protected $signature = 'fetch:articles';
    protected $description = 'Fetch articles from multiple news APIs and store them in the database';

    public function handle(){
        $sources = [
            [
                'name' => 'NewsAPI Org',
                'url' => 'https://newsapi.org/v2/top-headlines?country=us&apiKey=' . env('NEWSAPIORG_KEY'),
                'type' => 'api',
            ],
            [
                'name' => 'The Guardian',
                'url' => 'https://content.guardianapis.com/search?api-key=' . env('GUARDIAN_KEY'),
                'type' => 'api',
            ],
            [
                'name' => 'BBC News',
                'url' => 'http://feeds.bbci.co.uk/news/rss.xml',
                'type' => 'rss',
            ],
        ];
        $error = false;

        foreach ($sources as $source) {
            if ($source['type'] === 'rss') {
                $res= $this->fetchrsssrc($source['url'], $source['name']);
            } else {
                $res = $this->fetchapisrc($source['url'], $source['name']);
            }
            if (!$res) {
                $error = true;
            }
        }
        return $error ? 1 : 0;
    }


    protected function fetchapisrc($url, $sourceName){
        $response = Http::get($url);

        if ($response->successful()) {

            $this->processarticle($response->json(), $sourceName);
            $this->info("Fetched articles from {$sourceName}.");
            return 1;
        } else {
          //  Log::info("Response status: " . $response->status()); // Debugging line
            $this->error("Failed to fetch articles from {$sourceName}.");
            return false;
        }
    }


    

    // protected function fetchrsssrc($url, $sourceName){
    //     try {
    //         $xmlData = file_get_contents($url);
    //         $xml = simplexml_load_string($xmlData);
    //         if ($xml === false) {
    //          //   Log::error("Failed to parse XML from {$sourceName}");
    //             $this->error("Failed to parse XML from {$sourceName}");
    //             return false;
    //         }

    //         $articles = $xml->channel->item ?? [];
    //         $this->processrssarticle($articles, $sourceName);
    //         $this->info("Fetched articles from {$sourceName}.");
    //         return true;
    //     } catch (\Exception $e) {
    //     //    Log::error("Error fetching RSS from {$sourceName}: " . $e->getMessage());
    //         $this->error("Error fetching RSS from {$sourceName}: " . $e->getMessage());
    //         return false;
    //     }
    // }

    protected function fetchrsssrc($url, $sourceName){
        try {
            $response = Http::get($url);
            
            // Check if the response is successful
            if ($response->failed()) {
                $this->error("Failed to fetch RSS from {$sourceName}.");
                return false;
            }

            $xmlData = $response->body();
            $xml = simplexml_load_string($xmlData);

            // Check if XML parsing was successful
            if ($xml === false) {
                $this->error("Failed to parse XML from {$sourceName}");
                return false;
            }

            $articles = $xml->channel->item ?? [];
            $this->processrssarticle($articles, $sourceName);
            $this->info("Fetched articles from {$sourceName}.");
            return true;
        } catch (\Exception $e) {
            $this->error("Error fetching RSS from {$sourceName}: " . $e->getMessage());
            return false;
        }
    }

    protected function processarticle($response, $source){
        $articles = $source === 'The Guardian' ? $response['response']['results'] : $response['articles'] ?? [];

        foreach ($articles as $article) {
            $this->savearticle($this->maparticle($article, $source));
        }
    }

    protected function processrssarticle($articles, $source)
    {
        foreach ($articles as $article) {
            $mappedArticle = $this->maparticle($article, $source);
            if ($mappedArticle) {
                $this->savearticle($mappedArticle);
            }
        }
    }

    protected function maparticle($article, $source)
    {
        $dateString = $article['publishedAt'] ?? ($article->pubDate ?? null);
        $publishedAt = $dateString ? date('Y-m-d H:i:s', strtotime($dateString)) : null;
    
        if ($source === 'The Guardian') {
            $title = $article['webTitle'] ?? null; // title for Guardian
            $url = $article['webUrl'] ?? null; // URL for Guardian
        } else {
            $title = $article['title'] ?? (string) $article->title;
            $url = $article['url'] ?? (string) $article->link;
        }
    
        if (!$title || !$url) {
          //  Log::warning("Missing title or URL for article from {$source}");
            return null; 
        }
    
        $mapped = [
            'title' => $title,
            'url' => $url,
            'published_at' => $publishedAt,
            'source' => $source,
            'author' => $article['author'] ?? null,
            'content' => $article['description'] ?? null,
            'image_url' => $article['urlToImage'] ?? null,
        ];
    
        return array_filter($mapped);
    }

    protected function savearticle($data)
    {
        Article::updateOrCreate(['url' => $data['url']], $data);
    }
}
