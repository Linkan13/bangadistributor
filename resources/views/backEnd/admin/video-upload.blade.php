@extends('backEnd.master')

@section('styles')
<style>
    .form-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 25px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    video {
        width: 40%;
        max-height: 350px;
        margin-top: 15px;
        border-radius: 8px;
    }
</style>
@endsection

@section('mainContent')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="form-container">
        <h4 class="mb-4 text-center">Upload Video Section</h4>

        <form action="{{ route('admin.video.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Video File </label>
                <input type="file" name="video" class="form-control" >
            </div>

            @if (isset($story) && $story->video_url)
                <div class="mt-5 text-center">
                    <h5 class="mb-3">Current Uploaded Video</h5>
                    <video controls>
                        <source src="{{ url('public/' . $story->video_url) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
            <div class="mb-3">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required
                       value="{{ $story->title ?? '' }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $story->description ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ isset($story) && $story->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ isset($story) && !$story->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Upload Video</button>
            </div>
        </form>
    </div>

</div>
@endsection
