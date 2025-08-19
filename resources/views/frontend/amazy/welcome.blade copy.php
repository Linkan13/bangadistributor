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

<!-- <div id="Shop-categories" class="amaz_section amaz_deal_area second-section mt_60">
    <div class="container">
        <div class="row">
            @foreach($feature_categories->getCategoryByQuery()->take(3) as $key => $category)
            <div class="col-xl-4 col-md-6 col-lg-4 mb_20 img-container">
                @if(app('general_setting')->lazyload == 1)
                <img class="img-fluid lazyload" src="{{showImage(themeDefaultImg())}}" data-src="{{showImage(@$category->categoryImage->image?@$category->categoryImage->image:'frontend/default/img/default_category.png')}}" alt="{{@$category->name}}" title="{{@$category->name}}">
                @else
                <img class="img-fluid" src="{{showImage(@$category->categoryImage->image?@$category->categoryImage->image:'frontend/default/img/default_category.png')}}" alt="{{@$category->name}}" title="{{@$category->name}}">
                @endif
                <div class="amaz_inner_content">
                    <h3 class="amaz_title">{{textLimit($category->name,25)}}</h3>
                    <a href="{{route('frontend.category-product',['slug' => $category->slug, 'item' =>'category'])}}" class=" amaz_link">Explore collection <img src="{{showImage('frontend/default/img/arrow-up.svg')}}" class="svg-icon"></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div> -->


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
                <div class="product-item" data-category="womens">
                    <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=500&fit=crop" alt="{{ @$product->product_name }}" class="product-image">
                    <h3 class="product-name">{{ @$product->product_name }}</h3>
                    <p class="product-price">$89.99</p>
                    @if(isGuestAddtoCart())
                    <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
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
                <div class="product_widget5 style5" style="border:none">
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
                                class="lazyload">
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
                    <div class="product__met product__meta1 text-left">
                        <a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}">
                            <h4>@if ($product->product_name) {{ textLimit(@$product->product_name, 50) }} @else {{ textLimit(@$product->product->product_name, 50) }} @endif</h4>
                        </a>
                        @auth
                        <p>
                            @if (getProductwitoutDiscountPrice(@$product) != single_price(0))
                            <del>
                                {{getProductwitoutDiscountPrice(@$product)}}
                            </del>
                            @endif
                            <strong>
                                {{getProductDiscountedPrice(@$product)}}
                            </strong>
                        </p>
                        @endauth
                    </div>
                    @if(isGuestAddtoCart())
                    <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                        <div class="product_star">
                            @php
                            $reviews = @$product->reviews->where('status', 1)->pluck('rating');
                            if (count($reviews) > 0) {
                            $value = 0;
                            $rating = 0;
                            foreach ($reviews as $review) {
                            $value += $review;
                            }
                            $rating = $value / count($reviews);
                            $total_review = count($reviews);
                            } else {
                            $rating = 0;
                            $total_review = 0;
                            }
                            @endphp
                            <x-rating :rating="$rating" />
                        </div>
                        @auth
                        <a class="amaz_primary_btn addToCartFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
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
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M0.464844 1.14286C0.464844 0.78782 0.751726 0.5 1.10561 0.5H1.58256C2.39459 0.5 2.88079 1.04771 3.15883 1.55685C3.34414 1.89623 3.47821 2.28987 3.58307 2.64624C3.61147 2.64401 3.64024 2.64286 3.66934 2.64286H14.3464C15.0557 2.64286 15.5679 3.32379 15.3734 4.00811L13.8119 9.50163C13.5241 10.5142 12.6019 11.2124 11.5525 11.2124H6.47073C5.41263 11.2124 4.48508 10.5028 4.20505 9.47909L3.55532 7.10386L2.48004 3.4621L2.47829 3.45572C2.34527 2.96901 2.22042 2.51433 2.03491 2.1746C1.85475 1.84469 1.71115 1.78571 1.58256 1.78571H1.10561C0.751726 1.78571 0.464844 1.49789 0.464844 1.14286ZM4.79882 6.79169L5.44087 9.1388C5.56816 9.60414 5.98978 9.92669 6.47073 9.92669H11.5525C12.0295 9.92669 12.4487 9.60929 12.5795 9.14909L14.0634 3.92857H3.95529L4.78706 6.74583C4.79157 6.76109 4.79548 6.77634 4.79882 6.79169ZM7.72683 13.7857C7.72683 14.7325 6.96184 15.5 6.01812 15.5C5.07443 15.5 4.30942 14.7325 4.30942 13.7857C4.30942 12.8389 5.07443 12.0714 6.01812 12.0714C6.96184 12.0714 7.72683 12.8389 7.72683 13.7857ZM6.4453 13.7857C6.4453 13.5491 6.25405 13.3571 6.01812 13.3571C5.7822 13.3571 5.59095 13.5491 5.59095 13.7857C5.59095 14.0224 5.7822 14.2143 6.01812 14.2143C6.25405 14.2143 6.4453 14.0224 6.4453 13.7857ZM13.7073 13.7857C13.7073 14.7325 12.9423 15.5 11.9986 15.5C11.0549 15.5 10.2899 14.7325 10.2899 13.7857C10.2899 12.8389 11.0549 12.0714 11.9986 12.0714C12.9423 12.0714 13.7073 12.8389 13.7073 13.7857ZM12.4258 13.7857C12.4258 13.5491 12.2345 13.3571 11.9986 13.3571C11.7627 13.3571 11.5714 13.5491 11.5714 13.7857C11.5714 14.0224 11.7627 14.2143 11.9986 14.2143C12.2345 14.2143 12.4258 14.0224 12.4258 13.7857Z" fill="currentColor" />
                            </svg>
                            {{__('defaultTheme.add_to_cart')}}
                        </a>
                        @else
                        <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                            <a class="amaz_primary_btn w-100" style="text-indent: 0;" href="{{ url('/login') }}">
                                {{ __('defaultTheme.login_to_order') }}
                            </a>
                        </div>
                        @endauth
                    </div>
                    @else
                    <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                        <a class="amaz_primary_btn w-100" style="text-indent: 0;" href="{{ url('/login') }}">
                            {{__('defaultTheme.login_to_order')}}
                        </a>
                    </div>
                    @endif

                </div>

                @endforeach
            </div>

            <button class="cta-button">Browse All Products</button>
        </div>
    </section>

    <div id="new-arrivales" class="amaz_recomanded_area new-arrivales{{$more_products->status == 0?'d-none':''}}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="more_products" class="amaz_recomanded_box new-arrivales-box mb_60">
                        <div class="amaz_recomanded_box_head mb_20">
                            <h4 class="mb-10 box-title">{{$more_products->title}}</h4>
                            <h5 class="mb-0 box-sub-title">New arrival</h5>
                        </div>
                        <div class="amaz_recomanded_box_body2 dataApp">
                            @foreach($more_products->getHomePageProductByQuery() as $key => $product)
                            <div class="product_widget5 style5" style="border:none">
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
                                            class="lazyload">
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
                                <div class="product__met product__meta1 text-left">
                                    <a href="{{singleProductURL(@$product->seller->slug, $product->slug)}}">
                                        <h4>@if ($product->product_name) {{ textLimit(@$product->product_name, 50) }} @else {{ textLimit(@$product->product->product_name, 50) }} @endif</h4>
                                    </a>
                                    @auth
                                    <p>
                                        @if (getProductwitoutDiscountPrice(@$product) != single_price(0))
                                        <del>
                                            {{getProductwitoutDiscountPrice(@$product)}}
                                        </del>
                                        @endif
                                        <strong>
                                            {{getProductDiscountedPrice(@$product)}}
                                        </strong>
                                    </p>
                                    @endauth
                                </div>
                                @if(isGuestAddtoCart())
                                <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="product_star">
                                        @php
                                        $reviews = @$product->reviews->where('status', 1)->pluck('rating');
                                        if (count($reviews) > 0) {
                                        $value = 0;
                                        $rating = 0;
                                        foreach ($reviews as $review) {
                                        $value += $review;
                                        }
                                        $rating = $value / count($reviews);
                                        $total_review = count($reviews);
                                        } else {
                                        $rating = 0;
                                        $total_review = 0;
                                        }
                                        @endphp
                                        <x-rating :rating="$rating" />
                                    </div>
                                    @auth
                                    <a class="amaz_primary_btn addToCartFromThumnail" data-producttype="{{ @$product->product->product_type }}" data-seller={{ $product->user_id }} data-product-sku={{ @$product->skus->first()->id }}
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
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M0.464844 1.14286C0.464844 0.78782 0.751726 0.5 1.10561 0.5H1.58256C2.39459 0.5 2.88079 1.04771 3.15883 1.55685C3.34414 1.89623 3.47821 2.28987 3.58307 2.64624C3.61147 2.64401 3.64024 2.64286 3.66934 2.64286H14.3464C15.0557 2.64286 15.5679 3.32379 15.3734 4.00811L13.8119 9.50163C13.5241 10.5142 12.6019 11.2124 11.5525 11.2124H6.47073C5.41263 11.2124 4.48508 10.5028 4.20505 9.47909L3.55532 7.10386L2.48004 3.4621L2.47829 3.45572C2.34527 2.96901 2.22042 2.51433 2.03491 2.1746C1.85475 1.84469 1.71115 1.78571 1.58256 1.78571H1.10561C0.751726 1.78571 0.464844 1.49789 0.464844 1.14286ZM4.79882 6.79169L5.44087 9.1388C5.56816 9.60414 5.98978 9.92669 6.47073 9.92669H11.5525C12.0295 9.92669 12.4487 9.60929 12.5795 9.14909L14.0634 3.92857H3.95529L4.78706 6.74583C4.79157 6.76109 4.79548 6.77634 4.79882 6.79169ZM7.72683 13.7857C7.72683 14.7325 6.96184 15.5 6.01812 15.5C5.07443 15.5 4.30942 14.7325 4.30942 13.7857C4.30942 12.8389 5.07443 12.0714 6.01812 12.0714C6.96184 12.0714 7.72683 12.8389 7.72683 13.7857ZM6.4453 13.7857C6.4453 13.5491 6.25405 13.3571 6.01812 13.3571C5.7822 13.3571 5.59095 13.5491 5.59095 13.7857C5.59095 14.0224 5.7822 14.2143 6.01812 14.2143C6.25405 14.2143 6.4453 14.0224 6.4453 13.7857ZM13.7073 13.7857C13.7073 14.7325 12.9423 15.5 11.9986 15.5C11.0549 15.5 10.2899 14.7325 10.2899 13.7857C10.2899 12.8389 11.0549 12.0714 11.9986 12.0714C12.9423 12.0714 13.7073 12.8389 13.7073 13.7857ZM12.4258 13.7857C12.4258 13.5491 12.2345 13.3571 11.9986 13.3571C11.7627 13.3571 11.5714 13.5491 11.5714 13.7857C11.5714 14.0224 11.7627 14.2143 11.9986 14.2143C12.2345 14.2143 12.4258 14.0224 12.4258 13.7857Z" fill="currentColor" />
                                        </svg>
                                        {{__('defaultTheme.add_to_cart')}}
                                    </a>
                                    @else
                                    <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                                        <a class="amaz_primary_btn w-100" style="text-indent: 0;" href="{{ url('/login') }}">
                                            {{ __('defaultTheme.login_to_order') }}
                                        </a>
                                    </div>
                                    @endauth
                                </div>
                                @else
                                <div class="product_price d-flex align-items-center justify-content-between flex-wrap">
                                    <a class="amaz_primary_btn w-100" style="text-indent: 0;" href="{{ url('/login') }}">
                                        {{__('defaultTheme.login_to_order')}}
                                    </a>
                                </div>
                                @endif

                            </div>

                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    @if($more_products->getHomePageProductByQuery()->lastPage() > 1)
                    <a id="loadmore" class="amaz_primary_btn2 min_200 load_more_btn_homepage">{{__('common.load_more')}}</a>
                    @endif

                    <input type="hidden" id="login_check" value="@if(auth()->check()) 1 @else 0 @endif">
                </div>
            </div>
        </div>
    </div>



    <!-- cta::start  -->
    <div class="amaz_section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <x-random-ads-component />
                </div>
            </div>
        </div>
    </div>
    <!-- cta::end  -->

    <!-- amaz_recomanded::start  -->
    <!-- amaz_recomanded::end -->
    <x-top-brand-component />
    <!-- amaz_brand::start  -->
    <x-video-component :headers="$headers" />

    <!--<div class="amaz_section amaz-video-banner">-->
    <!--	<div class="container-fluid">-->
    <!--		<div class="row">-->
    <!--			<div class="col-xl-12">-->
    <!--				<div class="amaz_cta_box ">-->
    <!--					<div class="row justify-content-center ps_re">-->
    <!--						<img class="img-fluid w-100" src="{{showImage('frontend/default/img/video_bg-1-min.jpg')}}" alt="ads bar" title="ads bar">-->
    <!--						<div class="video-text-con">-->
    <!--							<div class="play-btn text-center mb_30">-->
    <!--								<svg width="94" height="93" viewBox="0 0 94 93" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.700195" y="-0.00732422" width="92.6" height="92.6" rx="46.3" fill="#ECB41F"/><g clip-path="url(#clip0_204_1142)"><path d="M37.9023 35.5928L56.3023 46.6328L37.9023 57.6728V35.5928Z" fill="white"/></g><defs><clipPath id="clip0_204_1142"><rect width="19" height="23" fill="white" transform="translate(37.5 34.7925)"/></clipPath></defs></svg>-->
    <!--							</div>-->
    <!--							<h4 class="video-title mb_30">From Threads to Trends!</h4>-->
    <!--							<p class="video-text">Here's our story!</p>-->
    <!--						</div>-->
    <!--					</div>-->
    <!--				</div>-->
    <!--			</div>-->
    <!--		</div>-->
    <!--	</div>-->
    <!--</div>-->
    <!-- amaz_brand::end  -->

    <div id="Pots-box" class="amaz_section amaz_deal_area amaz_post_box mt_60">
        <div class="container">
            <div class="row">
                <div class="amaz_recomanded_box_head mb_40">
                    <h4 class="mb-10 box-title">Cloth magazine</h4>
                    <h5 class="mb-0 box-sub-title">Love this amazing fashion articles</h5>
                </div>
            </div>
            <div class="row">
                @foreach($blogs as $blog)
                <div class="col-xl-3 col-md-6 col-lg-3 mb_20">
                    <div class="top-box ps_re">
                        <a href="blog/post/{{ $blog->slug }}" class="mb_30">
                            <img
                                src="{{ showImage($blog->image_url) }}"
                                alt="{{ $blog->title }}"
                                title="{{ $blog->title }}"
                                class="img-fluid">
                        </a>
                        <span class="cs-badge">Post</span>
                    </div>

                    <div class="btm-box mt_30">
                        <a href="{{ $blog->slug }}">
                            <h4 class="post-title">{{ Str::limit($blog->title, 70) }}</h4>
                        </a>
                        <div class="post-tags mt_10">
                            #{{ \Illuminate\Support\Str::of($blog->title)->snake()->explode('_')->first() ?? 'Fashion' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="amaz_banner_area mt_30">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <img data-src="{{ showImage('frontend/default/img/bnr-image.jpg') }}" src="{{ showImage('frontend/default/img/bnr-image.jpg') }}" class="img-fluid lazyload">
                </div>

            </div>
        </div>
    </div>

    <div id="amaz_contact" class="amaz_contact mt_60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title d-flex align-items-center gap-3 mb_30">
                        <!--h3 id="top_contact_title" class="m-0 flex-fill">bangaknitwear.us</h3-->
                        <img src="{{ showImage('frontend/default/img/bangaknitwear.us.png') }}" class="w-100 img_fluid lazyload">
                    </div>
                </div>
            </div>
            <div class="row mt_50">
                <div class="col-md-1 col-lg-3 col-xl-3"></div>
                <div class="col-md-10 col-lg-6 col-xl-6 form-box">
                    <div class="contact-form">
                        <x-contact-component :headers="$headers" />
                        <!--<form>-->
                        <!--	<div class="d-flex gap_40">-->
                        <!--		<div class="f-name flex_50">-->
                        <!--			<label>First Name</label><br>-->
                        <!--			<input type="text" name="first-name" class="first-name">-->
                        <!--		</div>-->

                        <!--		<div class="l-name flex_50">-->
                        <!--			<label>First Name</label><br>-->
                        <!--			<input type="text" name="last-name" class="last-name">-->
                        <!--		</div>-->
                        <!--	</div>-->

                        <!--	<div class="d-flex gap_40 mt_30">-->
                        <!--		<div class="e-mail flex_50">-->
                        <!--			<label>Email</label><br>-->
                        <!--			<input type="email" name="email" class="email">-->
                        <!--		</div>-->

                        <!--		<div class="phone flex_50">-->
                        <!--			<label>Phone</label><br>-->
                        <!--			<input type="tel" name="phone" class="phone" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" maxlength="12">-->
                        <!--		</div>-->
                        <!--	</div>-->

                        <!--	<div class="d-flex flx_clmn mt_30">-->
                        <!--		<div class="flex_100 title-txt">Select Subject?</div>-->
                        <!--		<div class="d-flex flex_100 mt_20">-->
                        <!--			<div class="flex_25 d-flex">-->
                        <!--				<input type="radio" id="html" name="fav_language" value="HTML">-->
                        <!--				<label class="radio" for="">General Inquiry</label>-->
                        <!--			</div>-->
                        <!--			<div class="flex_25 d-flex">-->
                        <!--				<input type="radio" id="html" name="fav_language" value="HTML">-->
                        <!--				<label class="radio" for="">General Inquiry</label>-->
                        <!--			</div>-->
                        <!--			<div class="flex_25 d-flex">-->
                        <!--				<input type="radio" id="html" name="fav_language" value="HTML">-->
                        <!--				<label class="radio" for="">General Inquiry</label>-->
                        <!--			</div>-->
                        <!--			<div class="flex_25 d-flex">-->
                        <!--				<input type="radio" id="html" name="fav_language" value="HTML">-->
                        <!--				<label class="radio" for="">General Inquiry</label>-->
                        <!--			</div>-->
                        <!--		</div>-->
                        <!--	</div>-->
                        <!--	<div class="d-flex mt_30">-->
                        <!--		<div class="message flex_100 d-flex flx_clmn">-->
                        <!--			<div class="flex_100"><label class="">Message</label></div>-->
                        <!--			<textarea class="message-text" placeholder="Write your message.."></textarea>-->
                        <!--		</div>-->

                        <!--	</div>-->
                        <!--	<div class="sbmt-btn-con text-right mt_20">-->
                        <!--		<button tyle="submit" class="sbmt-btn">Send Message</button>-->
                        <!--	</div>-->
                        <!--</form>-->
                    </div>
                </div>
                <div class="col-md-1 col-lg-3 col-xl-3"></div>
            </div>
        </div>
    </div>

    <div id="site_usp" class="amaz_section  amaz-site_usp mt_60">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-2 col-xl-2 d-flex align-items-center"></div>
                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3 d-flex align-items-center">
                    <div class="icon">
                        <img src="{{ showImage('frontend/default/img/guarantee.png') }}">
                    </div>
                    <div class="icon-text">
                        <div class="icon-title">Quality Check</div>
                        <div class="icon-subtitle">Best cloth with best stitch</div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3 d-flex align-items-center">
                    <div class="icon">
                        <img src="{{ showImage('frontend/default/img/shipping.png') }}">
                    </div>
                    <div class="icon-text">
                        <div class="icon-title">Shipping Worldwide</div>
                        <div class="icon-subtitle">Order 150 Countries</div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3 d-flex align-items-center">
                    <div class="icon">
                        <img src="{{ showImage('frontend/default/img/customer-support.png') }}">
                    </div>
                    <div class="icon-text">
                        <div class="icon-title">24 / 7 Support</div>
                        <div class="icon-subtitle">Dedicated support</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Searches::start  -->
    <x-popular-search-component />
    <!-- Popular Searches::end  -->

    @include(theme('partials._subscription_modal'))
    @endsection
    @include(theme('partials.add_to_cart_script'))
    @include(theme('partials.add_to_compare_script'))