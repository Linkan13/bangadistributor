<div class="main-title d-md-flex form_div_header">
    <h3 class="mb-3 mr-30 mb_xs_15px mb_sm_20px">{{__('product.add_category')}} </h3>
    @if (permissionCheck('product.bulk_category_upload_page'))
        <ul class="d-flex">
            <li><a class="primary-btn radius_30px mr-10 fix-gr-bg" href="{{ route('product.bulk_category_upload_page') }}"><i class="ti-plus"></i>{{ __('product.bulk_category_upload') }}</a></li>
        </ul>
    @endif
</div>
@if(isModuleActive('FrontendMultiLang'))
@php
$LanguageList = getLanguageList();
@endphp
@endif
<form method="POST" action="" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" id="add_category_form">
    <div class="white-box">
        <div class="add-visitor">
            <div class="row">
                @if(isModuleActive('FrontendMultiLang'))
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs justify-content-start mt-sm-md-20 mb-30 grid_gap_5" role="tablist">
                            @foreach ($LanguageList as $key => $language)
                                <li class="nav-item">
                                    <a class="nav-link anchore_color @if (auth()->user()->lang_code == $language->code) active @endif" href="#element{{$language->code}}" role="tab" data-toggle="tab" aria-selected="@if (auth()->user()->lang_code == $language->code) true @else false @endif">{{ $language->native }} </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($LanguageList as $key => $language)
                                <div role="tabpanel" class="tab-pane fade @if (auth()->user()->lang_code == $language->code) show active @endif" id="element{{$language->code}}">
                                    <div class="primary_input mb-25">
                                        <label class="primary_input_label" for="name">
                                            {{__('common.name')}}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="primary_input_field name" type="text" id="name{{auth()->user()->lang_code == $language->code?$language->code:''}}" name="name[{{$language->code}}]" autocomplete="off" placeholder="{{__('common.name')}}">
                                        <span class="text-danger" id="error_name_{{$language->code}}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="col-lg-12">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label" for="name">
                                {{__('common.name')}}
                                <span class="text-danger">*</span>
                            </label>
                            <input class="primary_input_field name" type="text" id="name" name="name" autocomplete="off"  placeholder="{{__('common.name')}}">
                            <span class="text-danger" id="error_name"></span>
                        </div>
                    </div>
                @endif
                    <div class="col-lg-12">
                        <div class="primary_input mb-25">
                            <label class="primary_input_label" for="slug">
                               {{__('common.slug')}}
                                <span class="text-danger">*</span>
                            </label>
                            <input class="primary_input_field slug" type="text" id="slug" name="slug" autocomplete="off" placeholder="{{__('common.slug')}}">
                            <span class="text-danger"  id="error_slug"></span>
                        </div>
                    </div>

                @if(isModuleActive('GoogleMerchantCenter'))
                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="name">
                            {{__('product.google_product_category_id')}}
                        </label>
                        <input class="primary_input_field google_product_category_id" type="number" min="0" step="{{step_decimal()}}" value="0" id="google_product_category_id" name="google_product_category_id" autocomplete="off"  placeholder="{{__('product.google_product_category_id')}}">
                        <span class="text-danger" id="error_google_product_category_id"></span>
                    </div>
                </div>
                @endif
                @if(isModuleActive('MultiVendor'))
                <div class="col-lg-12">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="name">
                            {{__('common.commission_rate')}}
                        </label>
                        <input class="primary_input_field commission_rate" type="number" min="0" step="{{step_decimal()}}" value="0" id="commission_rate" name="commission_rate" autocomplete="off"  placeholder="{{__('common.commission_rate')}}">
                        <span class="text-danger" id="error_commission_rate"></span>
                    </div>
                </div>
                @endif

                <div class="col-xl-12 mt-20">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">{{ __('product.searchable') }}</label>
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="searchable" id="searchable_active" value="1" checked="true"
                                        class="active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.active') }}</p>
                            </li>
                            <li>
                                <label data-id="color_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="searchable" id="searchable_inactive" value="0"
                                        class="de_active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.inactive') }}</p>
                            </li>
                        </ul>
                        <span class="text-danger" id="error_searchable"></span>
                    </div>
                </div>
                 <div class="col-xl-12">
                    <div class="primary_input">
                        <label class="primary_input_label" for="">{{ __('common.status') }}</label>
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="status" id="status_active" value="1" checked="true" class="active"
                                        type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.active') }}</p>
                            </li>
                            <li>
                                <label data-id="color_option" class="primary_checkbox d-flex mr-12 extra_width">
                                    <input name="status" value="0" id="status_inactive" class="de_active" type="radio">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('common.inactive') }}</p>
                            </li>
                        </ul>
                        <span class="text-danger" id="error_status"></span>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="primary_input">
                        <ul id="theme_nav" class="permission_list sms_list ">
                            <li>
                                <label data-id="bg_option" class="primary_checkbox d-flex mr-12">
                                    <input class="in_sub_cat" name="category_type" id="sub_cat" value="subCategory" type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                                <p>{{ __('product.add_as_sub_category') }}</p>
                            </li>
                        </ul>
                        <span class="text-danger" id=""></span>
                    </div>
                </div>
                <div class="col-xl-12 d-none in_parent_div" id="sub_cat_div">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="">{{ __('product.parent_category') }} <span class="text-danger">*</span></label>
                        <select class="mb-25" name="parent_id" id="parent_id">
                            <option selected disabled value="">{{__('common.select_one')}}</option>
                        </select>
                        <span class="text-danger" id="error_parent_id"></span>
                    </div>
                </div>
                <div class="col-xl-12 upload_photo_div">
                    <div class="primary_input">
                        <label class="primary_input_label">{{__('common.upload_photo')}} ({{getNumberTranslate(564)}} X {{getNumberTranslate(845)}}){{__('common.px')}}</label>
                    </div>
                </div>

                <div class="single_p col-xl-12 upload_photo_div">


                    <div class="primary_input mb-25">
                        <div class="primary_file_uploader" data-toggle="amazuploader" data-multiple="false" data-type="image" data-name="category_image">
                            <input class="primary-input file_amount" type="text" id="image" placeholder="{{ __('common.choose_images') }}" readonly="">
                            <button class="" type="button">
                                <label class="primary-btn small fix-gr-bg" for="thumbnail_image">{{__('product.Browse') }} </label>
                                <input type="hidden" class="selected_files image_selected_files" value="">
                            </button>
                            @if ($errors->has('category_image'))
                                <span class="text-danger"> {{ $errors->first('category_image') }}</span>
                            @endif
                        </div>
                        <div class="product_image_all_div">
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="icon_selector">
                    <div class="primary_input mb-25">
                        <label class="primary_input_label" for="icon">
                           {{__('common.icon')}} ({{ __('product.to_use_themefy_icon_please_type_here_or_select_fontawesome_from_list') }})
                        </label>
                        <input class="primary_input_field" type="text" id="icon" name="icon"
                        autocomplete="off" placeholder="{{__('common.icon')}}">
                        <span class="text-danger"  id="error_icon"></span>
                    </div>
                </div>

            </div>
            <div class="row mt-40">
                <div class="col-lg-12 text-center">
                    <button id="create_btn" type="submit" class="primary-btn fix-gr-bg submit_btn" data-toggle="tooltip" data-original-title=""><span class="ti-check"></span>{{__('common.save')}} </button>
                </div>
            </div>
        </div>
    </div>
</form>
