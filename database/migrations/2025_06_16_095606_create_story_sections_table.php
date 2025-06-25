<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('story_sections', function (Blueprint $table) {
            $table->id();
            $table->text('video_url')->nullable(); // URL of the video file
            $table->string('title')->nullable();      // Content for the <h3>
            $table->text('description')->nullable();  // Content for the <p>
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_sections');
    }
};
