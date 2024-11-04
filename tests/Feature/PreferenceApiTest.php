<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Preference;
use App\Models\Article;

class PreferenceApiTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_setPreferences_success(){
        
        $user = User::factory()->create();
        $response = $this->postJson('/api/preferences', [
            'user_id' => $user->id,
            'preferred_source' => 'NewsAPI Org',
            'preferred_category' => null,
            'preferred_author' => 'Nika Shakhnazarova'
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Preferences updated successfully.']);
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'preferred_source' => 'NewsAPI Org',
            'preferred_category' => null,
            'preferred_author' => 'Nika Shakhnazarova',
        ]);
    }



    public function test_setPreferences_fail(){
        
        $response = $this->postJson('/api/preferences', [
            'user_id' => 999, 
            'preferred_source' => 'NewsAPI Org',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['user_id']);
    }

    public function test_getPreferences_success(){
        
        $user = User::factory()->create();
        Preference::create([
            'user_id' => $user->id,
            'preferred_source' => 'NewsAPI Org',
            'preferred_category' => '',
            'preferred_author' => 'Nika Shakhnazarova',
        ]);
        $response = $this->getJson('/api/preferences?user_id=' . $user->id);
        $response->assertStatus(200)->assertJsonStructure(['preferences' => ['id', 'user_id', 'preferred_source', 'preferred_category', 'preferred_author']]);
    }

    public function test_getPreferences_fail(){
        $response = $this->getJson('/api/preferences?user_id=999'); // Non-existent user
        $response->assertStatus(422)->assertJsonValidationErrors(['user_id']);
    }

    public function test_personalizedFeed_success(){
        $user = User::factory()->create();
        $preference = Preference::create([
            'user_id' => $user->id,
            'preferred_source' => 'NewsAPI Org',
            'preferred_category' => '',
            'preferred_author' => 'Nika Shakhnazarova',
        ]);
        Article::factory()->create([
            
            'title' => '‘Nobody here feels ignored’: Harris and Trump barnstorm Wisconsin - POLITICO', 
            'source' => 'NewsAPI Org',
            'category' => '',
            'author' => 'Irie Sentner, Lisa Kashinsky, Jessica Piper',
            'url' => 'https://www.politico.com/news/2024/10/30/harris-trump-wisconsin-campaign-2024-00186427',

        ]);
         $response = $this->getJson('/api/personalized-feed?user_id=' . $user->id);
         $response->assertStatus(200)->assertJsonStructure(['current_page', 'data' => ['*' => ['id', 'title', 'content', 'author', 'source', 'category']]]);
    }

  
    public function test_personalizedFeed_fail() {
      
        $user = User::factory()->create();
        $response = $this->getJson('/api/personalized-feed?user_id=' . $user->id);
        $response->assertStatus(404)->assertJson(['message' => 'No preferences set.']);
    }
}

