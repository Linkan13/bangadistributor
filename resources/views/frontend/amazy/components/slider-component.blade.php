@php
$headerSliderSection = $headers->where('type','slider')->first();
@endphp

<div class="bannerUi_active owl-carousel {{$headerSliderSection->is_enable == 0?'d-none':''}}">
    @php
    $sliders = $headerSliderSection->sliders();
    @endphp
    @if(count($sliders) > 0)
    @foreach($sliders as $key => $slider)
    @php
    $parts = explode('~~', @$slider->name);
    $part0 = $parts[0] ?? '';
    $part1 = $parts[1] ?? '';
    $part2 = $parts[2] ?? '';
    @endphp
    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-background">
            <img class="img-fluid" src="{{showImage($slider->slider_image)}}" alt="{{ $part0 }}" title="{{ $part0 }}">
        </div>
        <div class="hero-content">
            <h1 class="hero-title">{!! $part0 !!}</h1>
            <p class="hero-subtitle">{!! $part1 !!}</p>
            <button class="hero-cta" data-scroll-to="product-listings">Shop Now</button>
        </div>
    </section>

    <!-- <a class="banner_img" href="
                @if($slider->data_type == 'url')
                    {{$slider->url}}
                @elseif($slider->data_type == 'product')
                    {{singleProductURL(@$slider->product->seller->slug, @$slider->product->slug)}}
                @elseif($slider->data_type == 'category')
                    {{route('frontend.category-product',['slug' => @$slider->category->slug, 'item' =>'category'])}}
                @elseif($slider->data_type == 'brand')
                    {{route('frontend.category-product',['slug' => @$slider->brand->slug, 'item' =>'brand'])}}
                @elseif($slider->data_type == 'tag')
                    {{route('frontend.category-product',['slug' => @$slider->tag->name, 'item' =>'tag'])}}
                @else
                    {{url('/category')}}
                @endif
                " {{$slider->is_newtab == 1?'target="_blank"':''}}>
                <img class="img-fluid" src="{{showImage($slider->slider_image)}}" alt="{{ $part0 }}" title="{{ $part0 }}">
                <div class="slide-content">

                    <h4 class="slide-title">{!! $part0 !!}</h4>
                    @if($part1)
                        <p class="slide-des">{!! $part1 !!}</p>
                    @endif
                    @if($part2)
                        <span class="amaz_primary_btn slide-btn">{{ $part2 }}</span>
                    @endif
				</div>
            </a> -->
    @endforeach
    @endif
</div>