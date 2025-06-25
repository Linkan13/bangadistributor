<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StorySection extends Model
{
    use HasFactory;

    protected $table = 'story_sections';

    protected $fillable = [
        'video_url',
        'title',
        'description',
    ];
}
