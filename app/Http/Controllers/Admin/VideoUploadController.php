<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StorySection;

class VideoUploadController extends Controller
{
    public function showForm()
    {
        $story = StorySection::first();
        return view('backEnd.admin.video-upload', compact('story'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'video' => 'nullable|mimes:mp4,webm,mov|max:51200',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|in:0,1',
        ]);

        $videoUrl = null;
        $video = $request->file('video');

        if ($video) {
            $destinationPath = public_path('uploads/videos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $filename = uniqid() . '.' . $video->getClientOriginalExtension();
            $video->move($destinationPath, $filename);
            $videoUrl = 'uploads/videos/' . $filename;
        }

        $storySection = StorySection::first();
        $isActive = $request->has('is_active') ? $request->is_active : 0;

        if ($storySection) {
            if ($videoUrl) {
                $oldPath = public_path($storySection->video_url);
                if ($storySection->video_url && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
                $storySection->video_url = $videoUrl;
            }

            $storySection->title = $request->title;
            $storySection->description = $request->description;
            $storySection->is_active = $isActive;
            $storySection->save();
        } else {
            StorySection::create([
                'video_url' => $videoUrl ?? '',
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => $isActive,
            ]);
        }

        return redirect()->back()->with('success', 'Video uploaded successfully!');
    }
}
