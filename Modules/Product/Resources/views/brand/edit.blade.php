@extends('backEnd.master')
@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/product/css/style.css'))}}" />
@endsection
@section('mainContent')
@if(isModuleActive('FrontendMultiLang'))
@php
$LanguageList = getLanguageList();
@endphp
@endif
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.edit_brand') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{route("product.brand.update", $brand->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="white_box_50px box_shadow_white mb-20">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-2 mr-30">{{ __('product.brand_info') }}</h3>
                                    </div>
                                </div>
                                @if(isModuleActive('FrontendMultiLang'))
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs justify-content-start mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
                                        @foreach ($LanguageList as $key => $language)
                                            <li class="nav-item">
                                                <a class="nav-link default_lang anchore_color @if (auth()->user()->lang_code == $language->code) active @endif" data-id="{{$language->code}}" href="#element{{$language->code}}" role="tab" data-toggle="tab" aria-selected="@if (auth()->user()->lang_code == $language->code) true @else false @endif">{{ $language->native }} </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach ($LanguageList as $key => $language)
                                            <div role="tabpanel" class="tab-pane fade @if (auth()->user()->lang_code == $language->code) show active @endif" id="element{{$language->code}}">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{__("common.name")}} <span class="text-danger">*</span></label>
                                                        <input class="primary_input_field" name="name[{{$language->code}}]" placeholder="{{__("common.name")}}" type="text" value="{{isset($brand)?$brand->getTranslation('name',$language->code):old('name.'.$language->code)}}">
                                                        @error('name.'.auth()->user()->lang_code)
                                                        <span class="text-danger">{{$message}}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{__("common.description")}} </label>
                                                        <textarea class="summernote" name="description[{{$language->code}}]"> {{isset($brand)?$brand->getTranslation('description',$language->code):old('description.'.$language->code)}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for=""> {{__("common.name")}} <span class="text-danger">*</span></label>
                                        <input class="primary_input_field" name="name" placeholder="{{__("common.name")}}" type="text" value="{{$brand->name}}">
                                        @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for=""> {{__("common.description")}} </label>
                                        <textarea class="summernote" name="description">{{$brand->description}}</textarea>
                                    </div>
                                </div>
                            @endif
                                <div class="col-lg-12">
                                    <div class="primary_input mb-30">
                                        <label class="primary_input_label" for=""> {{__("product.website_link")}}</label>
                                        <input class="primary_input_field" name="link" placeholder="{{__("product.website_link")}}" type="text" value="{{old('link')}}">
                                        <span class="text-danger">{{$errors->first('link')}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-2 mr-30">{{ __('common.seo_info') }}</h3>
                                    </div>
                                </div>
                                @if(isModuleActive('FrontendMultiLang'))
                                <div class="col-lg-12">
                                    <div class="tab-content">
                                        @foreach ($LanguageList as $key => $language)
                                            <div role="tabpanel" class="tab-pane pelement fade @if (auth()->user()->lang_code == $language->code) show active @endif" id="pelement{{$language->code}}">
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{__("common.meta_title")}}</label>
                                                        <input class="primary_input_field" name="meta_title[{{$language->code}}]" placeholder="{{__("common.meta_title")}}" type="text" value="{{isset($brand)?$brand->getTranslation('meta_title',$language->code):old('meta_title.'.$language->code)}}">
                                                        <span class="text-danger">{{$errors->first('meta_title')}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="primary_input mb-15">
                                                        <label class="primary_input_label" for=""> {{__("common.meta_description")}}</label>
                                                        <textarea class="primary_textarea height_112 meta_description" placeholder="{{ __('common.meta_description') }}" name="meta_description[{{$language->code}}]" spellcheck="false"> {{isset($brand)?$brand->getTranslation('meta_description',$language->code):old('meta_description.'.$language->code)}}</textarea>
                                                        <span class="text-danger">{{$errors->first('meta_description')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for=""> {{__("common.meta_title")}}</label>
                                        <input class="primary_input_field" name="meta_title" placeholder="{{__("common.meta_title")}}" type="text" value="{{$brand->meta_title}}">
                                        <span class="text-danger">{{$errors->first('meta_title')}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="primary_input mb-15">
                                        <label class="primary_input_label" for=""> {{__("common.meta_description")}}</label>
                                        <textarea class="primary_textarea height_112 meta_description" placeholder="{{ __('common.meta_description') }}" name="meta_description" spellcheck="false">{{$brand->meta_description}}</textarea>
                                        <span class="text-danger">{{$errors->first('meta_description')}}</span>
                                    </div>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="white_box_50px box_shadow_white">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-2 mr-30">{{ __('common.status_info') }}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="">{{ __('common.status') }} <span class="text-danger">*</span></label>
                                        <select class="primary_select mb-25" name="status" id="status">
                                            <option value="1" @if ($brand->status == 1) selected @endif>{{ __('common.publish') }}</option>
                                            <option value="0" @if ($brand->status == 0) selected @endif>{{ __('common.pending') }}</option>
                                        </select>
                                        @error('status')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-2 mr-30">{{ __('common.logo') }} ({{getNumberTranslate(310)}} X {{getNumberTranslate(460)}}){{__('common.px')}}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="primary_input mb-25">
                                        <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="brand_image">
                                            <input class="primary-input file_amount" type="text" id="image" placeholder="{{ __('common.choose_images') }}" readonly="">
                                            <button class="" type="button">
                                                <label class="primary-btn small fix-gr-bg" for="thumbnail_image">{{__('product.Browse') }} </label>
                                                <input type="hidden" class="selected_files image_selected_files" value="">
                                            </button>
                                            @if ($errors->has('brand_image'))
                                                <span class="text-danger"> {{ $errors->first('brand_image') }}</span>
                                            @endif
                                        </div>
                                        <div class="product_image_all_div">
                                            @if(@$brand->brand_image_media->media_id)
                                                <input type="hidden" name="brand_image" class="product_images_hidden" value="{{@$brand->brand_image_media->media_id}}">
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-12">
                                    <div class="main-title d-flex">
                                        <h3 class="mb-2 mr-30">{{ __('common.is_featured') }}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="primary_input mb-30">
                                        <label class="switch_toggle" for="active_checkbox1">
                                            <input type="checkbox" id="active_checkbox1" name="featured" @if ($brand->featured == 1) checked @endif>
                                            <div class="slider round"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="primary_btn_2"><i class="ti-check"></i>{{__("common.update")}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";
            $(document).ready(function () {
                $('.summernote').summernote({
                    height: 200,
                    codeviewFilter: true,
			        codeviewIframeFilter: true
                });
                $(document).on('change', '#brand_image', function(event){
                    getFileName($('#brand_image').val(),'#image');
                    imageChangeWithFile($(this)[0],'#MetaImgDiv');
                });
                @if(isModuleActive('FrontendMultiLang'))
                    $(document).on('click', '.default_lang', function(event){
                        var lang = $(this).data('id');
                        $('.pelement').removeClass('active show');
                        $('#pelement'+lang).addClass('active show');
                        if (lang == "{{auth()->user()->lang_code}}") {
                            $('#default_lang_{{auth()->user()->lang_code}}').removeClass('d-none');
                        }
                    });
                    if ("{{auth()->user()->lang_code}}") {
                            $('#default_lang_{{auth()->user()->lang_code}}').removeClass('d-none');
                    }
                @endif
            });
        })(jQuery);
    </script>
@endpush
