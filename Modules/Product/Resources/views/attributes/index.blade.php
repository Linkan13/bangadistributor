@extends('backEnd.master')
@section('styles')
<link rel="stylesheet" href="{{asset(asset_path('modules/product/css/style.css'))}}" />
@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-lg-4">

                    <div class="form_div">
                        @include('product::attributes.create_attribute')
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('product.attribute') }} {{__('common.list')}}</h3>
                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="">
                                <div id="variant_list">
                                    @include('product::attributes.attributes_list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="app_base_url" id="app_base_url" value="{{ url()->to('/') }}">
    <div class="show_div">

    </div>
@if (permissionCheck('product.attribute.destroy'))
    @include('backEnd.partials.delete_modal')
@endif

@endsection
@include('product::attributes.scripts')
