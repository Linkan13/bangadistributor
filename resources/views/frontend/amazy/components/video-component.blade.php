
@push('styles')
<style>
@media screen and (max-width: 1399px) {
    .section-info h3, .story-section .story-wrapper h3, .contact-section .contact-heading h3 {
        font-size: 50px;
    }
}
.story-section .story-wrapper h3 {
    font-size: 60px;
    line-height: 1.3;
    margin-bottom: 0;
}.story-section .story-wrapper p {
    color: #ffffff;
    font-size: 24px;
    line-height: 1.3;
}
.video-background iframe, .video-background video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.story-section .story-wrapper {
    color: white;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
}
.story-section .story-wrapper {
    display: flex
;
    flex-direction: column;
    row-gap: 20px;
    color: #ffffff;
}
.story-section {
    min-height: 80vh;
    display: flex
;
    align-items: center;
    justify-content: center;
}
.story-section {
    margin: 40px 0;
    background-size: cover;
    background-position: center;
    padding: 250px 16px;
    text-align: center;
}
</style>
@endpush

@if ($story && $story->is_active)
<section class="story-section position-relative overflow-hidden">
    <div class="video-background position-absolute top-0 start-0 w-100 h-100 z-n1">
        <video class="w-100 h-100 object-fit-cover" autoplay loop muted playsinline preload="metadata" style="object-fit: cover;">
            <source src="{{ url($story->video_url) }}" type="video/mp4">
        </video>
    </div>
    <div class="container position-relative z-1 text-center py-5 text-white">
        <div class="story-wrapper">
            <h3 class="mt-3">{{ $story->title }}</h3>
            <p class="mb-0">{{ $story->description }}</p>
        </div>
    </div>
</section>
@endif
