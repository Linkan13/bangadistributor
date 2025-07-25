@extends('backEnd.master')
@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/giftcard/css/style.css'))}}" />

@endsection
@section('mainContent')
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="">
            <div class="col-lg-12">
                <div class="main-title">
                    <h3 class="mb-30">
                        {{__('product.edit_gift_card')}} </h3>
                </div>
            </div>
        </div>
        <div id="formHtml" class="col-lg-12">
            <div class="white-box">
                <form action="{{route('admin.giftcard.update',$card->id)}}" id="edit_form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="add-visitor">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="name">{{ __('common.name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="primary_input_field" type="text" id="name" name="name"
                                                autocomplete="off" value="{{old('name')??$card->name}}" placeholder="{{ __('common.name') }}">

                                            @error('name')
                                                <span class="text-danger" id="error_name">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="sku">{{ __('product.sku') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="primary_input_field" type="text" id="sku" name="sku"
                                                autocomplete="off" value="{{old('sku')??$card->sku}}" placeholder="{{ __('product.sku') }}">

                                            @error('sku')
                                                <span class="text-danger" id="error_sku">{{$message}}</span>
                                            @enderror
                                        </div>


                                    </div>
                                    <div class="col-lg-6">

                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="selling_price">{{ __('product.selling_price') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="primary_input_field" type="number" id="selling_price" name="selling_price" min="0" step="{{step_decimal()}}"
                                                autocomplete="off" value="{{old('selling_price')??$card->selling_price}}" placeholder="{{ __('product.selling_price') }}">
                                            @error('selling_price')
                                                <span class="text-danger" id="error_selling_price">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-lg-6 {{ $card->type != null ? 'd-none':'' }}">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="in_app_purchase_code">{{ __('product.in_app_purchase_code') }} <span
                                                    class="text-danger">*</span></label>
                                            <input class="primary_input_field" type="text" id="in_app_purchase_code" name="reedem_in_app_purchase_code"
                                                autocomplete="off" value="{{old('in_app_purchase_code')??$card->in_app_purchase}}" placeholder="{{ __('product.in_app_purchase_code') }}">
                                            @error('in_app_purchase_code')
                                                <span class="text-danger" id="in_app_purchase_code">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{ __('product.shipping_methods') }}
                                                <span class="text-danger">*</span></label>
                                            <select class="primary_select mb-25" name="shipping_id" id="shipping_method" disabled>
                                                @foreach($shippings as $key => $shipping)
                                                <option value="{{$shipping->id}}" @if ($card->shipping_id == $shipping->id) selected @endif>{{ $shipping->method_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('shipping_id')
                                                <span class="text-danger" id="error_shipping_method">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-lg-6">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for=""> {{ __('product.discount') }} </label>
                                            <input class="primary_input_field" name="discount" id="discount"
                                                placeholder="{{old('discount')??$card->discount}}" type="number" min="0" step="{{step_decimal()}}"
                                                value="{{ $card->discount }}">

                                            <span class="text-danger">{{ $errors->first('discount') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{ __('product.discount_type') }}</label>
                                            <select class="primary_select mb-25" name="discount_type" id="discount_type">
                                                <option {{$card->discount_type == 1?'selected':''}} value="1">{{ __('common.amount') }}</option>
                                                <option {{$card->discount_type == 0?'selected':''}} value="0">{{ __('common.percentage') }}</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-lg-6">
                                        <div class="primary_input mb-15">
                                            <label class="primary_input_label" for="">{{__('product.discount_period')}}</label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input placeholder="{{ __('common.date') }}" class="primary_input_field primary-input form-control" id="date" type="text" name="date" value="{{old('date')??date('d-m-Y',strtotime($card->start_date)).' to '.date('d-m-Y',strtotime($card->end_date))}}" autocomplete="off" readonly>

                                                        </div>
                                                        <input type="hidden" name="start_date" id="start_date" value="{{old('start_date')??date('d-m-Y',strtotime($card->start_date))}}">
                                                        <input type="hidden" name="end_date" id="end_date" value="{{old('end_date')??date('d-m-Y',strtotime($card->end_date))}}">
                                                    </div>
                                                    <button class="" type="button">
                                                        <i class="ti-calendar" id="start-date-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('date')
                                                <span class="text-danger" id="error_date">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="primary_input mb-25">

                                            <label for="">@lang('blog.tags') (@lang('product.comma_separated'))<span class="text-danger">*</span></label>

                                            <div class="tagInput_field mb_26">
                                                @php
                                                    $tags = [];
                                                    foreach ($card->tags as $tag) {
                                                        $tags[] = $tag->name;
                                                    }
                                                    $tags = implode(',', $tags);
                                                @endphp
                                                <input name="tags" class="tag-input" id="tag-input-upload-shots"
                                                    type="text" value="{{ $tags }}" data-role="tagsinput" />
                                            </div>
                                            <span class="text-danger" id="error_tags">{{ $errors->first('tags') }}</span>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-xl-8 col-lg-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{ __('product.thumbnail_image') }} ({{ getNumberTranslate(165) }} X
                                                {{ getNumberTranslate(165) }}){{ __('common.px') }}
                                                <span class="text-danger">*</span></label>
                                            <div class="primary_file_uploader">
                                                <input class="primary-input" type="text" id="thumbnail_image_file"
                                                    placeholder="{{ __('product.thumbnail_image') }}" readonly="">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="thumbnail_image">{{ __('product.Browse') }} </label>
                                                    <input type="file" class="d-none" name="thumbnail_image" id="thumbnail_image">
                                                </button>
                                            </div>
                                            <input type="hidden" name="old_thbmnail_image" value="{{ $card->thumbnail_image }}">
                                            @error('thumbnail_image')
                                            <span class="text-danger" id="error_message">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12">
                                        <div class="thumb_img_div">
                                        <img id="ThumbnailImg" src="{{ showImage($card->thumbnail_image??'backend/img/default.png')}}" alt="">
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div id="gallery_img_prev">
                                            @foreach($card->galaryImages as $key => $image)
                                            <div class="galary_img_div">
                                                <img class="galaryImg" src="{{showImage($image->image_name)}}" alt="">
                                            </div>
                                            @endforeach
                                        </div>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="primary_input mb-25">
                                            <label class="primary_input_label" for="">{{ __('product.galary_image') }} ({{ getNumberTranslate(400) }} X
                                                {{ getNumberTranslate(400) }}){{ __('common.px') }}</label>
                                            <div class="primary_file_uploader">
                                                <input class="primary-input" type="text" id="placeholderFileOneName"
                                                    placeholder="{{ __('product.galary_image') }}" readonly="">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="galary_image">{{ __('product.Browse') }} </label>
                                                    <input type="file" class="d-none" name="galary_image[]" id="galary_image" multiple>
                                                </button>
                                            </div>
                                            @error('galary_image.*')
                                                <span class="text-danger" id="error_message">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">{{ __('common.status') }}</label>
                                            <ul id="theme_nav" class="permission_list sms_list ">
                                                <li>
                                                    <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                                        <input name="status" id="status_active" value="1" {{$card->status==1?'checked':''}} class="active"
                                                            type="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('common.active') }}</p>
                                                </li>
                                                <li>
                                                    <label data-id="color_option" class="primary_checkbox d-flex mr-12">
                                                        <input name="status" value="0" id="status_inactive" {{$card->status==0?'checked':''}} class="de_active" type="radio">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <p>{{ __('common.inactive') }}</p>
                                                </li>
                                            </ul>
                                            @error('status')
                                            <span class="text-danger" id="status_error">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="primary_input mb-25">
                                    <label class="primary_input_label" for="description">{{ __('common.description') }}</label>

                                        <textarea name="description" class="summernote" id="description" class="">{{old('description')??$card->description}}</textarea>
                                </div>
                                @error('description')
                                <span class="text-danger" id="error_message">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-40">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="col-lg-12 text-center">
                                    <button id="submit_btn" type="submit" class="primary-btn fix-gr-bg"
                                        data-toggle="tooltip" title="" data-original-title="">
                                        <span class="ti-check"></span>
                                        {{ __('common.update') }} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
    <script>

        (function($){
            "use strict";

            $(document).ready(function(){
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = mm + '/' + dd + '/' + yyyy;

                $('#date').daterangepicker({
                    "timePicker": false,
                    "linkedCalendars": false,
                    "autoUpdateInput": false,
                    "showCustomRangeLabel": false,
                    "startDate": "{{date('mm/dd/Y',strtotime($card->start_date))}}",
                    "endDate": "{{date('mm/dd/Y',strtotime($card->end_date))}}"
                }, function(start, end, label) {
                    $('#date').val(start.format('DD-MM-YYYY')+' to ' + end.format('DD-MM-YYYY'));
                    $('#start_date').val(start.format('DD-MM-YYYY'));
                    $('#end_date').val(end.format('DD-MM-YYYY'));
                });


                $('#description').summernote({
                    placeholder: '',
                    tabsize: 2,
                    height: 400,
                    codeviewFilter: true,
			        codeviewIframeFilter: true
                });

                $(document).on('submit', '#add_form', function(event){
                    resetValidationErrors();
                });

                $(document).on('change', '#thumbnail_image', function(event){
                    getFileName($('#thumbnail_image').val(),'#thumbnail_image_file');
                    imageChangeWithFile($(this)[0],'#ThumbnailImg');
                });

                $(document).on('change', '#galary_image', function(event){
                    galleryImage($(this)[0],'#galler_img_prev')
                });

                $(document).on('keyup', '#name', function(event){
                    processSlug($('#name').val(), '#sku')
                });

                function resetValidationErrors() {
                    $('#error_name').text('');
                    $('#error_amount').text('');
                    $('#error_status').text('');
                    $('#error_date').text('');
                    $('#error_description').text('');
                }

                function galleryImage(data, divId) {
                    if (data.files) {

                        $.each(data.files, function(key, value) {
                            $('#gallery_img_prev').empty();
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#gallery_img_prev').append(
                                    `
                                    <div class="galary_img_div">
                                        <img class="galaryImg" src="`+ e.target.result +`" alt="">
                                    </div>
                                    `
                                );


                            };
                            reader.readAsDataURL(value);
                        });
                    }
                }

            });
        })(jQuery);


    </script>
@endpush
