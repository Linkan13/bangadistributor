@php
   $popular_search = Modules\FrontendCMS\Entities\HomePageSection::where('section_name','popular_search')->first();
@endphp
<div id="popular-searches" class="amaz_section amaz-popular-searches mt_60 {{ ($popular_search) ? ($popular_search->status == 0?'d-none':'') : ''}}">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section__title d-flex align-items-center gap-3 mb_20">
					<h3 class="m-0 flex-fill">{{ $popular_search->title }}</h3>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
			     @if($search_items->count())
                    @foreach($search_items as $item)
                        <span class="search-term"><a class="popular_search_list" style="text-decoration: none;color: #000;" href="{{url('/').'/category'.'/'.$item->keyword.'?item=search&category=0'}}">{{$item->keyword}}</a></span>
                    @endforeach
                @else
                    <p>{{__('amazy.no_search_keyword_found')}}</p>
                @endif
			</div>
		</div>
	</div>
</div>