@extends('backEnd.master')
@section('styles')
<style>
.anchore_color{
color: #415094;
}
</style>
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
                <div class="col-lg-4">

                    <div class="create_div">
                        @include('refund::admin.refund_process.create')
                    </div>
                    <div class="edit_div d-none">
                        @include('refund::admin.refund_process.edit')
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('refund.refund_process') }}</h3>
                        </div>
                    </div>
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <!-- table-responsive -->
                            <div class="">
                                <div id="refund_process_list">
                                    @include('refund::admin.refund_process.process_list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="app_base_url" id="app_base_url" value="{{ url()->to('/') }}">

@include('backEnd.partials.delete_modal')
@endsection
@include('refund::admin.refund_process.scripts')
