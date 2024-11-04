<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';
    
    protected $fillable = ['user_id', 'preferred_source', 'preferred_category', 'preferred_author'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
