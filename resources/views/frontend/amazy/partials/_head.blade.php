<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@if(trim($__env->yieldContent('title'))) @yield('title') | {{app('general_setting')->meta_site_title}} @else {{app('general_setting')->meta_site_title}} @endif</title>
    <meta name="_token" content="@php echo csrf_token(); @endphp" >
    @if(request()->route()->getName() == 'frontend.welcome')
     <meta name="description" content="{{app('general_setting')->meta_description}}">
     <meta name="keywords" content="{{app('general_setting')->meta_tags}}">
    @endif
    @section('share_meta')


        @show
    @laravelPWA
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="@php echo csrf_token(); @endphp" />
    <link rel="shortcut icon" type="image/x-icon" href="{{showImage(app('general_setting')->favicon)}}">
    <link rel="icon" href="{{showImage(app('general_setting')->favicon)}}" type="image/png">
    @php
        $themeColor = Modules\Appearance\Entities\ThemeColor::where('status',1)->first();
        if($themeColor->id == 1){
            $themeColor = (object)[
                'background_color' => '#fff',
                'base_color' => '#FF2732',
                'text_color' => '#00124e',
                'feature_color' => '#fff',
                'footer_background_color' => '#000',
                'footer_text_color' => '#fff',
                'navbar_color' => '#fff',
                'menu_color' => '#f9f9fd',
                'border_color' => '#f1ece8',
                'success_color' => '#4BCF90',
                'warning_color' => '#E09079',
                'danger_color' => '#FF6D68',
            ];
        }
    @endphp
    <style>
        :root {
            --background_color : {{ $themeColor->background_color }};
            --base_color : {{ $themeColor->base_color }};
            --text_color : {{ $themeColor->text_color }};
            --feature_color : {{ $themeColor->feature_color }};
            --footer_background_color : {{ $themeColor->footer_background_color }};
            --footer_text_color : {{ $themeColor->footer_text_color }};
            --navbar_color : {{ $themeColor->navbar_color }};
            --menu_color : {{ $themeColor->menu_color }};
            --border_color : {{ $themeColor->border_color }};
            --success_color : {{ $themeColor->success_color }};
            --warning_color : {{ $themeColor->warning_color }};
            --danger_color : {{ $themeColor->danger_color }};
            --base_color_10 :{{ $themeColor->base_color }}1a;
            --base_color_20 :{{ $themeColor->base_color }}33;
            --base_color_30 :{{ $themeColor->base_color }}4d;
            --base_color_60 :{{ $themeColor->base_color }}99;
        }
        .top_menu_icon{
            color: var(--base_color) !important;
        }
        .toast-success {
            background-color: {{ $themeColor->success_color }}!important;
        }
        .toast-error{
            background-color: {{ $themeColor->danger_color }}!important;
        }
        .toast-warning{
            background-color: {{ $themeColor->warning_color }}!important;
        }
        .newsletter_form_wrapper .newsletter_form_inner .newsletter_form_thumb {
            height: 100%;
            background-image: url({{ showImage($popupContent->image) }});
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .promotion_bar_wrapper{
            background-image: url({{ showImage(@$promotionbar->image) }})!important;
        }
        @media (max-width: 768px) {
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form_thumb {
                height: 100%!important;
            }
        }
        @media (max-width: 575.98px) {
            .fb_dialog_content iframe{
                bottom:60px!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner {
                width: 400px!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form_thumb {
                height: 600px!important;
                opacity: .3;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form {
                padding: 30px;
            }
        }
        @media (max-width: 395px) {
            .newsletter_form_wrapper .newsletter_form_inner {
                width: 385px!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form {
                top: 125px;
            }
            .message_div, .message_div_modal {
                min-height: 10px;
            }
        }
        @media (max-width: 375px) {
            .newsletter_form_wrapper .newsletter_form_inner {
                width: 345px!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner {
                height: 550px!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form_thumb {
                height: 550px!important;
            }
        }
        @media only screen and (max-width: 896px) and (max-height: 414px) {
            .newsletter_form_wrapper .newsletter_form_inner {
                height: 410px;
            }
        }
        @media only screen and (max-width: 720px) and (max-height: 540px) {
            .newsletter_form_thumb{
                display: none!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner {
                height: 335px;
                width: 600px;
            }
        }
        @media only screen and (max-width: 653px) and (max-height: 280px) {
            .newsletter_form_wrapper .newsletter_form_inner {
                height: 335px;
                width: 600px;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form h3 {
                font-size: 20px;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form p {
                margin: 5px 0 5px;
            }
            .newsletter_form_wrapper .newsletter_form_inner .close_modal {
                top: 30px;
            }
        }
        @media only screen and (max-width: 280px) and (max-height: 653px) {
            .newsletter_form_thumb{
                display: none!important;
            }
            .newsletter_form_wrapper .newsletter_form_inner {
                height: 400px!important;
                width: 260px!important;
            }
            #top_bar{
                display: none;
            }
            .newsletter_form_wrapper .newsletter_form_inner .newsletter_form {
                padding: 35px 10px;
                margin-top: 0px;
                top: 40px;
            }
        }
        .popular_search_lists .popular_search_list {
            overflow: hidden;
            word-wrap: break-word;
        }
    </style>
    <!-- CSS here -->
    @if(isRtl())
    <link rel="stylesheet" href="{{asset('frontend/amazy/compile_css/rtl_app.css')}}" >
    @else
    <link rel="stylesheet"  href="{{asset('frontend/amazy/compile_css/app.css')}}" >
    @endif
    @stack('styles')
    @if (app('business_settings')->where('type', 'google_analytics')->first()->status == 1)
          <!-- Global site tag (gtag.js) - Google Analytics -->
          <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('ANALYTICS_TRACKING_ID') }}"></script>
          <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '{{ env('MEASUREMENT_ID') }}');
          </script>
          <!-- Google Analytics Code -->
           <x-google-analytics-client-id />
          <!-- </head> -->
    @endif
    @if (app('business_settings')->where('type', 'facebook_pixel')->first()->status == 1)
          <!-- Facebook Pixel Code -->
          <script>
              !function(f,b,e,v,n,t,s)
              {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
              n.callMethod.apply(n,arguments):n.queue.push(arguments)};
              if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
              n.queue=[];t=b.createElement(e);t.async=!0;
              t.src=v;s=b.getElementsByTagName(e)[0];
              s.parentNode.insertBefore(t,s)}(window, document,'script',
              'https://connect.facebook.net/en_US/fbevents.js');
              fbq('init', "{{ Modules\Setup\Entities\AnalyticsTool::where('type', 'facebook_pixel')->first()->facebook_pixel_id }}");
              fbq('track', 'PageView');
          </script>
          <noscript>
              <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ Modules\Setup\Entities\AnalyticsTool::where('type', 'facebook_pixel')->first()->facebook_pixel_id }}/&ev=PageView&noscript=1"/>
          </noscript>
          <!-- End Facebook Pixel Code -->
    @endif

        @if(auth()->check() && !auth()->user()->phone)
            <style>
                .dashboard_sidebar_wized .dashboard_sidebar_wized_user .user_desc .email_text::before {
                    background: transparent!important;
                }
            </style>
        @endif

    <link rel="stylesheet" href="{{asset(asset_path('css/custom.css'))}}">
    <script>
        const _config = {!!  json_encode(collect(app('general_setting'))->only(['currency_symbol','decimal_limit','currency_symbol_position']))  !!};
        const _user_currency = {!!  json_encode(collect(app('user_currency'))->only(['symbol','convert_rate']))  !!};
    </script>
</head>
