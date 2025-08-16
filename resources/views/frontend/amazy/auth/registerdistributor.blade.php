@extends('frontend.amazy.auth.layouts.app')
@push('styles')
    <style>
        .primary_bulet_checkbox .checkmark{
            top: 2px;
        }
        .term_link_set, .policy_link_set{
            color: var(--base_color);
        }
    </style>
@endpush
@section('content')
<div class="amazy_login_area">
    <div class="amazy_login_area_left d-flex align-items-center justify-content-center">
        <div class="amazy_login_form">
            <a href="{{url('/')}}" class="logo mb_50 d-block">
                <img src="{{showImage(app('general_setting')->logo)}}" alt="{{app('general_setting')->company_name}}" title="{{app('general_setting')->company_name}}">
            </a>
            <h3 class="m-0">{{__('auth.Sign Up')}}</h3>
            <p class="support_text">{{__('auth.See your growth and get consulting support!')}}</p>

            @if (session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('distributorpost.register') }}" method="POST" name="register" id="register_form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    @if(!empty($row) && !empty($form_data))
                        @php
                            $default_field = [];
                            $custom_field = [];
                        @endphp

                         @foreach($form_data as $row)
                            @php
                                if($row->type != 'header' && $row->type !='paragraph'){
                                    if(property_exists($row,'className') && strpos($row->className, 'default-field') !== false){
                                        $default_field[] = $row->name;
                                    }else{
                                        $custom_field[] = $row->name;
                                    }
                                    $required = property_exists($row,'required');
                                    $type = property_exists($row,'subtype') ? $row->subtype : $row->type;
                                    $placeholder = property_exists($row,'placeholder') ? $row->placeholder : trans('formBuilder.'.$row->label);
                                }
                            @endphp

                            @if($row->type =='header' || $row->type =='paragraph')
                                <div class="col-lg-12">
                                    <{{ $row->subtype }}>{{trans('formBuilder.'.$row->label)}}</{{ $row->subtype }}>
                                </div>
                            @elseif($row->type == 'text' || $row->type == 'number' || $row->type == 'email' || $row->type == 'date')
                                <div class="col-12 mb_20">
                                    <label for="{{$row->name}}" class="primary_label2"> {{trans('formBuilder.'.$row->label)}} @if($required) <span class="text-danger">*</span> @endif</label>
                                    <input {{$required ? 'required' :''}} type="{{$type}}" id="{{$row->name}}" class="primary_input3 radius_5px @error($row->name) is-invalid @enderror" name="{{$row->name}}" value="{{ old($row->name) }}" placeholder="{{trans('formBuilder.'.$row->label)}}">
                                    @error($row->name)
                                    <span class="text-danger" >{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif($row->type=='select')
                                <div class="col-xl-12 mb_25">
                                    <div class="form-group input_div_mb">
                                        <label class="primary_label2 style4" for={{$row->name}}>{{trans('formBuilder.'.$row->label)}}@if($required) <span class="text-danger">*</span> @endif</label>
                                        <select {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" class=" theme_select style2 wide">
                                            @foreach($row->values as $value)
                                                <option value="{{$value->value}}" {{old($row->name) == $value->value? 'selected': ''}}>{{$value->label}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{$errors->first($row->name)}}</span>
                                    </div>
                                </div>

                            @elseif($row->type == 'date')
                                <div class="col-12 mb_30">
                                    <label for="start_datepicker" class="primary_label2 style2 "> {{trans('formBuilder.'.$row->label)}} @if($required) <span class="text-danger">*</span> @endif</label>
                                    <input {{$required ? 'required' :''}} type="{{$type}}" id="start_datepicker" class="primary_input3 style4 mb-0 @error($row->name) is-invalid @enderror" name="{{$row->name}}" value="{{ old($row->name) }}" placeholder="{{trans('formBuilder.'.$row->label)}}">
                                    @error($row->name)
                                    <span class="text-danger" >{{ $message }}</span>
                                    @enderror
                                </div>

                            @elseif($row->type=='textarea')
                                <div class="col-md-12 mb-10">
                                    <label for={{$row->name}}>{{trans('formBuilder.'.$row->label)}}@if($required) <span class="text-danger">*</span> @endif</label>
                                    <textarea class="form-control" {{$required ? 'required' :''}} name="{{$row->name}}" id="{{$row->name}}" placeholder="{{trans('formBuilder.'.$row->label)}}">{{old($row->name)}}</textarea>
                                    <span class="text-danger">{{$errors->first($row->name)}}</span>
                                </div>

                            @elseif($row->type=="radio-group")
                                <div class="col-lg-12 mb_20">
                                    <label for="">{{trans('formBuilder.'.$row->label)}}</label>
                                    <div class="d-flex radio-btn-flex">
                                        @foreach($row->values as $value)
                                            <label class="primary_bulet_checkbox">
                                                <input type="radio" name="{{ $row->name }}" class="payment_method" value="{{ $value->value }}">
                                                <span class="checkmark"></span>
                                            </label>
                                            <a class="ml_10 mr_10 text_color">{{ $value->label }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($row->type=="checkbox-group")
                                <div class="col-12 mb_25">
                                    <label>{{trans('formBuilder.'.$row->label)}}</label>
                                    @foreach($row->values as $value)
                                    <label class="primary_checkbox d-flex">
                                        <input value="{{ $value->value }}" id="term_check" name="{{ $row->name }}[]" checked type="checkbox">
                                        <span class="checkmark mr_15"></span>
                                        <span class="label_name f_w_400 ">{{$value->label}}</span>
                                        <span id="error_term_check" class="text-danger"></span>
                                    </label>
                                    @endforeach
                                </div>

                            @elseif($row->type =='file')
                                <div class="col-lg-12 mb_20">
                                    <label for="{{$row->name}}" class="primary_label2 style3">{{trans('formBuilder.'.$row->label)}}@if($required) <span class="text-danger">*</span> @endif</label>
                                    <input type="{{$type}}" accept="image/*" class="primary_input3 style4 radius_3px pd_12" name="{{$row->name}}" id="{{$row->name}}" >
                                </div>
                            @elseif($row->type =='checkbox')
                                <div class="col-md-12 mb_20 mt_10">
                                    <label class="primary_checkbox d-flex">
                                        <input id="policyCheck" type="checkbox" checked>
                                        <span class="checkmark mr_15"></span>
                                        <span class="label_name f_w_400 ">{!! $row->label !!}</span>
                                    </label>
                                </div>
                            @endif

                        @endforeach

                    @else
                        <div class="col-12 mb_20">
                            <label class="primary_label2">{{__('formBuilder.first_name')}} <span>*</span> </label>
                            <input name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="{{ __('formBuilder.first_name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.first_name') }}'" class="primary_input3 radius_5px" type="text">
                            <span class="text-danger" >{{ $errors->first('first_name') }}</span>
                        </div>
                        <div class="col-12 mb_20">
                            <label class="primary_label2">{{__('formBuilder.last_name')}}</label>
                            <input name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="{{ __('formBuilder.last_name') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.last_name') }}'" class="primary_input3 radius_5px" type="text">
                            <span class="text-danger" >{{ $errors->first('last_name') }}</span>
                        </div>
                        <div class="col-12 mb_20">
                            <label class="primary_label2">email <span>*</span></label>
                            <input name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('formBuilder.email') }}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{ __('common.email') }}'" class="primary_input3 radius_5px" type="text">
                            <span class="text-danger" >{{ $errors->first('email') }}</span>
                        </div>
                        <div class="col-12 mb_20">
                            <label class="primary_label2">{{ __('formBuilder.password') }} <span>*</span></label>
                            <input name="password" id="password" placeholder="{{__('amazy.Min. 8 Character')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('amazy.Min. 8 Character')}}'" class="primary_input3 radius_5px" type="password">
                            <span class="text-danger" >{{ $errors->first('password') }}</span>
                        </div>
                        <div class="col-12 mb_20">
                            <label class="primary_label2" for="password-confirm">{{ __('formBuilder.confirm_password') }} <span>*</span></label>
                            <input name="password_confirmation" id="password-confirm" placeholder="{{__('amazy.Min. 8 Character')}}" onfocus="this.placeholder = ''" onblur="this.placeholder = '{{__('amazy.Min. 8 Character')}}'" class="primary_input3 radius_5px" type="password">
                        </div>


                    @endif

                    @if(env('NOCAPTCHA_FOR_REG') == "true")
                    <div class="col-12 mb_20">
                        @if(env('NOCAPTCHA_INVISIBLE') != "true")
                        <div class="g-recaptcha" data-callback="callback" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"></div>
                        @endif
                        <span class="text-danger" >{{ $errors->first('g-recaptcha-response') }}</span>
                    </div>
                    @endif
                    <div class="col-12">
                        @if(env('NOCAPTCHA_INVISIBLE') == "true")
                        <button type="button" class="g-recaptcha amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}" data-size="invisible" data-callback="onSubmit">{{__('auth.Sign Up')}}</button>
                        @else
                        <button class="amaz_primary_btn style2 radius_5px  w-100 text-uppercase  text-center mb_25" id="sign_in_btn">{{__('auth.Sign Up')}}</button>
                        @endif
                    </div>
                    <div class="col-lg-12 mb_20 mt_10">
                        <label class="primary_checkbox d-flex">
                            <input id="policyCheck" type="checkbox" >
                            <span class="checkmark mr_15"></span>
                            <p class="label_name f_w_400"> {{__('formBuilder.By signing up, you agree to ')}} <a href="/privacy-policy-terms-and-conditions">{{__('formBuilder.Terms of Service ')}}</a> {{ __('formBuilder.and') }} <a href="/privacy-policy-terms-and-conditions">{{__('formBuilder.Privacy Policy')}}</a></p>
                        </label>
                    </div>
                    <div class="col-12">
                        <p class="sign_up_text">{{__('auth.Already have an Account?')}}  <a href="{{url('/distributor/login')}}">{{__('auth.Sign In')}}</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="amazy_login_area_right d-flex align-items-center justify-content-center">
        <div class="amazy_login_area_right_inner d-flex align-items-center justify-content-center flex-column">
            <div class="thumb">
                <img class="img-fluid" src="{{ showImage($loginPageInfo->cover_img) }}" alt="{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}" title="{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}">
            </div>
            <div class="login_text d-flex align-items-center justify-content-center flex-column text-center">
                <h4>{{ isset($loginPageInfo->title)? $loginPageInfo->title:'' }}</h4>
                <p class="m-0">{{ isset($loginPageInfo->sub_title)? $loginPageInfo->sub_title:'' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("register_form").submit();
    }
</script>
<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $(document).on('submit', '#register_form', function(event){
                if($("#policyCheck").prop('checked')!=true){
                    event.preventDefault();
                    toastr.error("{{__('common.please_agree_with_our_policy_privacy')}}","{{__('common.error')}}");
                    return false;
                }
            });
        });
    })(jQuery);
</script>
@endpush
