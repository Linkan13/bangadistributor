<div id="Brands" class="amaz_section amaz_deal_area amaz_brands mt_60 {{$top_brands->status == 0?'d-none':''}}">
	<div class="container">
		<div class="row">
			<div class="amaz_recomanded_box_head mb_40">
				<h4 class="mb-10 box-title">{{$top_brands->title}}</h4>
				<h5 class="mb-0 box-sub-title">Our Catalogue</h5>
			</div>
		</div>
		<div class="row">
		    @php
                $customBrandImages = [
                    'frontend/default/img/nk.png',
                    'frontend/default/img/add.png',
                    'frontend/default/img/jky.png',
                    'frontend/default/img/rebk.png',
                ];
            @endphp

		     @foreach($top_brands->getBrandByQuery()->take(4) as $key => $brand)
		        @php
                    $customImage = showImage($customBrandImages[$key]);
                @endphp
    		@php
                $brandParts = explode('~~', $brand->name);
                $brandCountryCode = count($brandParts) > 1 ? strtolower(end($brandParts)) : null;
                $brandNameWithoutCountry = implode('-', array_slice($brandParts, 0, -1));
            
                // Check: show flag only if brandCountryCode exists and is 2 letters
                $flagUrl = null;
                if ($brandCountryCode && preg_match('/^[a-z]{2}$/', $brandCountryCode)) {
                    $flagUrl = "https://flagcdn.com/w40/{$brandCountryCode}.png";
                }
            @endphp
            
            <div class="col-xl-3 col-md-6 col-lg-3 mb_20 ps_re">
                <a href="{{ route('frontend.category-product', ['slug' => $brand->slug, 'item' => 'brand']) }}" class="mb_30">
                    <img src="{{ showImage($brand->logo ?: 'frontend/default/img/brand_image.png') }}"
                         alt="{{ $brandNameWithoutCountry }}" title="{{ $brandNameWithoutCountry }}" class="img-fluid">
                </a>
            
                @if ($flagUrl)
                    <span class="cs-badge">
                        <img src="{{ $flagUrl }}" alt="{{ $brandCountryCode }} flag" class="ms-2" style="height: 16px; vertical-align: middle;">
                    </span>
                @endif
            
                @if ($flagUrl)
                    <div class="logo-box">
                        <span class="cs-badge-title">{{ $brandNameWithoutCountry }}</span>
                    </div>
                @else
                    <div class="logo-box">
                        <span class="cs-badge-title">{{ $brand->name }}</span>
                    </div>
                @endif
            </div>


            @endforeach
		</div>
		<div class="row text-center mt_30 mb_60">
			<div class="col-sm-12 btm-text">Proudly crafting in India, delivering premium knitwear to fashion markets <span>worldwide.</span></div>
		</div>
	</div>
</div>