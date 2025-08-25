@extends('frontend.amazy.layouts.app')

@push('styles')
<style>
    .banner_img {
        width: 100%;
        position: relative;
        overflow: hidden;
        display: block;
        padding-bottom: 31.5%;
    }

    .banner_img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
@endpush

@section('content')
<!-- home_banner::start  -->
@php
$headers = \Modules\Appearance\Entities\Header::all();
@endphp
<x-slider-component :headers="$headers" />
<!-- home_banner::end  -->
@php
$best_deal = $widgets->where('section_name','best_deals')->first();
@endphp

@php
$feature_categories = $widgets->where('section_name','feature_categories')->first();
@endphp



<!-- Main Content -->
<main>
    @php
    $more_products = $widgets->where('section_name','more_products')->first();
    @endphp
    <!-- Product Listings Section -->
    <section class="product-listings" id="product-listings">
        <div class="container">
            <div class="section-header">
                <h2 class="script-heading">Product Listings</h2>
                <h1 class="main-heading">Timeless Curated Excellence</h1>
            </div>

            <div class="category-tabs">
                @foreach($feature_categories->getCategoryByQuery()->take(3) as $key => $category)
                <div class="tab {{ $key == 0 ? 'active' : '' }}" data-category="{{textLimit($category->name,25)}}">
                    {{textLimit($category->name,25)}}
                </div>
                @endforeach
            </div>

            <div class="products-grid" id="products-grid">
                @foreach($more_products->getHomePageProductByQuery() as $key => $product)
                @php
                if (@$product->thum_img != null) {
                $thumbnail = showImage(@$product->thum_img);
                } else {
                $thumbnail = showImage(@$product->product->thumbnail_image_source);
                }

                $price_qty = getProductDiscountedPrice(@$product);
                $showData = [
                'name' => @$product->product_name,
                'url' => singleProductURL(@$product->seller->slug, @$product->slug),
                'price' => $price_qty,
                'thumbnail' => $thumbnail,
                ];
                @endphp
                <div class="product-item product_widget5 style5" data-category="womens">
                    <div class="product_thumb_upper">
                    @php
                    if (@$product->thum_img != null) {
                    $thumbnail = showImage(@$product->thum_img);
                    } else {
                    $thumbnail = showImage(@$product->product->thumbnail_image_source);
                    }

                    $price_qty = getProductDiscountedPrice(@$product);
                    $showData = [
                    'name' => @$product->product_name,
                    'url' => singleProductURL(@$product->seller->slug, @$product->slug),
                    'price' => $price_qty,
                    'thumbnail' => $thumbnail,
                    ];
                    @endphp
                    <a href="{{ singleProductURL($product->seller->slug, $product->slug) }}"
                        class="thumb">
                        @if(app('general_setting')->lazyload == 1)
                        <img data-src="{{ $thumbnail }}" src="{{ showImage(themeDefaultImg()) }}"
                            alt="{{ @$product->product_name }}" title="{{ @$product->product_name }}"
                            class="product-image lazyload">
                        @else
                        <img src="{{ $thumbnail }}" alt="{{ @$product->product_name }}" title="{{ @$product->product_name }}">
                        @endif
                    </a>
                    @if(isGuestAddtoCart())
                        <div class="product_action">
                            <a href="javascript:void(0)" class="addToCompareFromThumnail"
                                data-producttype="{{ @$product->product->product_type }}"
                                data-seller={{ $product->user_id }}
                                data-product-sku={{ @$product->skus->first()->id }}
                                data-product-id={{ $product->id }}>
                                <i class="ti-control-shuffle"
                                    title="{{ __('defaultTheme.compare') }}"></i>
                            </a>
                            <a href="javascript:void(0)"
                                class="add_to_wishlist {{ $product->is_wishlist() == 1 ? 'is_wishlist' : '' }}"
                                id="wishlistbtn_{{ $product->id }}"
                                data-product_id="{{ $product->id }}"
                                data-seller_id="{{ $product->user_id }}">
                                <i class="far fa-heart" title="{{ __('defaultTheme.wishlist') }}"></i>
                            </a>
                            <a class="quickView" data-product_id="{{ $product->id }}"
                                data-type="product">
                                <i class="ti-eye" title="{{ __('defaultTheme.quick_view') }}"></i>
                            </a>
                        </div>
                        @endif
                        <div class="product_badge">
                            @if(isGuestAddtoCart())
                            @if($product->hasDeal)
                            @if($product->hasDeal->discount >0)
                            <span class="d-flex align-items-center discount">
                                @if($product->hasDeal->discount_type ==0)
                                {{getNumberTranslate($product->hasDeal->discount)}} % {{__('common.off')}}
                                @else
                                {{single_price($product->hasDeal->discount)}} {{__('common.off')}}
                                @endif
                            </span>
                            @endif
                            @else
                            @if($product->hasDiscount == 'yes')
                            @if($product->discount >0)
                            <span class="d-flex align-items-center discount">
                                @if($product->discount_type ==0)
                                {{getNumberTranslate($product->discount)}} % {{__('common.off')}}
                                @else
                                {{single_price($product->discount)}} {{__('common.off')}}
                                @endif
                            </span>
                            @endif
                            @endif
                            @endif
                            @endif
                            @if(isModuleActive('ClubPoint'))
                            <span class="d-flex align-items-center point">
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none">
                                    <path d="M15 7.6087V10.087C15 11.1609 12.4191 12.5652 9.23529 12.5652C6.05153 12.5652 3.47059 11.1609 3.47059 10.087V8.02174M3.71271 8.2357C4.42506 9.18404 6.628 10.0737 9.23529 10.0737C12.4191 10.0737 15 8.74704 15 7.60704C15 6.96683 14.1872 6.26548 12.9115 5.77313M12.5294 3.47826V5.95652C12.5294 7.03044 9.94847 8.43478 6.76471 8.43478C3.58094 8.43478 1 7.03044 1 5.95652V3.47826M6.76471 5.9433C9.94847 5.9433 12.5294 4.61661 12.5294 3.47661C12.5294 2.33578 9.94847 1 6.76471 1C3.58094 1 1 2.33578 1 3.47661C1 4.61661 3.58094 5.9433 6.76471 5.9433Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{getNumberTranslate(@$product->product->club_point)}}
                            </span>
                            @endif
                            @if(isModuleActive('WholeSale') && @$product->skus->first()->wholeSalePrices != '')
                            <span class="d-flex align-items-center sale">{{__('common.wholesale')}}</span>
                            @endif
                        </div>
                        </div>
                    <!-- <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=500&fit=crop" alt="{{ @$product->product_name }}" class="product-image"> -->
                    <h3 class="product-name">{{ @$product->product_name }}</h3>
                    <p class="product-price">
                        @auth
                            @if (getProductwitoutDiscountPrice(@$product) != single_price(0))
                                <del>
                                    {{getProductwitoutDiscountPrice(@$product)}}
                                </del>
                            @endif
                                <strong>
                                    {{getProductDiscountedPrice(@$product)}}
                                </strong>
                        @endauth
                    </p>
                    @if(isGuestAddtoCart())
                    <div class="">
                        @auth
                        <a class="add-to-cart amaz_primary_btn addToCartFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
                            @if (@$product->hasDeal)
                            data-base-price={{ selling_price(@$product->skus->first()->sell_price,@$product->hasDeal->discount_type,@$product->hasDeal->discount) }}
                            @else
                            @if (@$product->hasDiscount == 'yes')
                            data-base-price={{ selling_price(@$product->skus->first()->sell_price,@$product->discount_type,@$product->discount) }}
                            @else
                            data-base-price={{ @$product->skus->first()->sell_price }}
                            @endif
                            @endif
                            data-shipping-method=0
                            data-product-id={{ $product->id }}
                            data-stock_manage="{{$product->stock_manage}}"
                            data-stock="{{@$product->skus->first()->product_stock}}"
                            data-min_qty="{{@$product->product->minimum_order_qty}}"
                            data-prod_info="{{ json_encode($showData) }}"
                            >
                            {{__('defaultTheme.add_to_cart')}}
                        </a>

                        @else
                        <a class="add-to-cart" href="{{ url('/login') }}">{{__('defaultTheme.login_to_order')}}</a>
                        @endauth
                    </div>
                    @else
                    <a class="add-to-cart" href="{{ url('/login') }}">{{__('defaultTheme.login_to_order')}}</a>
                    @endif

                </div>
                @endforeach
            </div>

            <button class="cta-button">Browse All Products</button>
        </div>
    </section>
 <!-- About Section -->
 <section class="about-section" id="about-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="script-heading">About the Manufacturer</h2>
                    <h1 class="main-heading">Rooted in Craft. Designed for Legacy.</h1>
                </div>

                <div class="about-images">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=253&h=282&fit=crop" alt="Manufacturing" class="about-image-small">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=427&fit=crop" alt="Manufacturing" class="about-image-large">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=256&h=282&fit=crop" alt="Manufacturing" class="about-image-right">
                </div>

                <button class="cta-button">Meet the Makers</button>
            </div>
        </section>

        <!-- How We Sell Section -->
        <section class="how-we-sell">
            <div class="container">
                <div class="section-header">
                    <h2 class="script-heading">How do we sell ?</h2>
                    <h1 class="main-heading">Seamless Sourcing, Built for Trust</h1>
                </div>

                <div class="process-content">
                    <div class="process-steps">
                        <div class="step-carousel" id="step-carousel">
                            <div class="step-images">
                                <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=405&h=405&fit=crop" alt="Step 1" class="step-image active" data-step="0">
                                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=405&h=405&fit=crop" alt="Step 2" class="step-image" data-step="1">
                                <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=405&h=405&fit=crop" alt="Step 3" class="step-image" data-step="2">
                            </div>
                            <div class="carousel-controls">
                                <button class="carousel-btn prev" id="prev-step">‹</button>
                                <div class="carousel-dots">
                                    <span class="dot active" data-step="0"></span>
                                    <span class="dot" data-step="1"></span>
                                    <span class="dot" data-step="2"></span>
                                </div>
                                <button class="carousel-btn next" id="next-step">›</button>
                            </div>
                        </div>
                        <div class="step-texts">
                            <div class="step active" data-step="0">
                                <h3>Stock or Custom</h3>
                                <p>Choose a ready-made stock package or create your own custom stock options.</p>
                            </div>
                            <div class="step" data-step="1">
                                <h3>Quote and Buy</h3>
                                <p>Get a quotation for the bonds you've selected and add them to your inventory.</p>
                            </div>
                            <div class="step" data-step="2">
                                <h3>Sell at your Store</h3>
                                <p>After Purchase, Sell them in your store or dropship from our inventory.</p>
                            </div>
                        </div>
                    </div>
                    <div class="process-main-image">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=600&fit=crop" alt="Main Process" class="main-process-image">
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Exploration -->
        <section class="category-exploration">
            <div class="container">
                <div class="section-header">
                    <h2 class="script-heading">Explore by Category</h2>
                    <h1 class="main-heading">Let your style do the talking.</h1>
                </div>

                <div class="category-grid">
                    @foreach($feature_categories->getCategoryByQuery()->take(3) as $key => $category)
                        <div class="category-item" >
                            <img class="img-fluid lazyload" src="{{showImage(themeDefaultImg())}}" data-src="{{showImage(@$category->categoryImage->image?@$category->categoryImage->image:'frontend/default/img/default_category.png')}}" alt="{{@$category->name}}" title="{{@$category->name}}">
                            <div class="category-overlay">
                                <h4>
                                    <a href="{{route('frontend.category-product',['slug' => $category->slug, 'item' =>'category'])}}" class=" amaz_link">{{textLimit($category->name,25)}}</a>
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="cta-button">Browse All Products</button>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured-products">
            <div class="container">
                <div class="featured-content">
                    <div class="featured-text">
                        <h2 class="script-heading">Featured Products</h2>
                        <h1 class="main-heading">Not just a collection<br>a curation of excellence.</h1>
                        <button class="cta-button">View Featured Line</button>
                    </div>
                </div>

                <div class="featured-background">
                    <img src="{{ showImage('frontend/default/img/bangaknitwear.us.png') }}" class="lazyload">
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="script-heading">Frequently Asked Questions</h2>
                    <h1 class="main-heading">Answers to your most common questions.</h1>
                </div>

                <div class="faq-list">
                    @foreach($FaqList as $index => $faq)
                        <div class="faq-item {{ $index == 0 ? 'expanded' : '' }}">
                            <div class="faq-question">
                                <span>{{ $faq->getAttributes()['title'] }}</span>
                                <span class="faq-icon">{{ $index == 0 ? '-' : '+' }}</span>
                            </div>
                            <div class="faq-answer">
                                <p>{{ $faq->getAttributes()['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section" id="contact-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="script-heading">Get in Touch</h2>
                    <h1 class="main-heading">We'd love to hear from you.</h1>
                </div>

                <div class="contact-content">
                    <p class="contact-description">Feel free to contact us with a project proposal, quote or estimation, or simply to say hello.</p>
                    <x-contact-component :headers="$headers" />


                    <div class="success-message" id="success-message">
                        <p>Thank you for your message! We'll get back to you soon.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>




    @include(theme('partials._subscription_modal'))
    @endsection
    @include(theme('partials.add_to_cart_script'))
    @include(theme('partials.add_to_compare_script'))