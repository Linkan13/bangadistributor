<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\StorySection;

class VideoComponent extends Component
{
    public $story;

    public function __construct()
    {
        $this->story = StorySection::first();
    }

    public function render()
    {
        return view(theme('components.video-component'), [
            'story' => $this->story
        ]);
    }
}
