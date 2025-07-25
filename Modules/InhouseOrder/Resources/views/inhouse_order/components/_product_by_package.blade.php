<div class="col-lg-8">
    <div class="main-title d-flex">
        <h3 class="mb-3 mr-30">{{ __('shipping.order_packages') }}</h3>
    </div>
    @php
        $items = 0;
        $index  = 0;
        $totalItem = 0;
        $subtotal = 0;
        $actualtotal = 0;
        $shippingtotal = 0;
        $empty_check = 0;
        foreach ($cartData as $data) {
            $empty_check += count($data);
            $items += count($data);
        }
        $gstAmountTotal = 0;
    @endphp
    @if(count($cartData))

        @foreach($cartData as $key => $cartItems)
            @php
                $seller = App\Models\User::where('id',$key)->first();
            @endphp
            @php
                $addtional_charge = 0;
                foreach($cartItems as $item){
                    $addtional_charge += $item->product->sku->additional_shipping;
                }
                $index ++;
                $package_wise_shipping = session()->get('inhouseOrderShippingCost')[$key];
                $shippingtotal += $package_wise_shipping['shipping_cost'] + $package_wise_shipping['additional_cost'];
                $package_wise_shipping_cost = $package_wise_shipping['shipping_cost'] + $package_wise_shipping['additional_cost'];
            @endphp
                <div class="card mb-10">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-4">
                                <p>{{__('common.package')}} {{$index}} {{__('common.of')}} {{$items}}</p>
                            </div>
                            <div class="col-lg-4">
                                @if(isModuleActive('MultiVendor'))
                                <p>{{__('common.seller')}}: <strong>@if($seller->role->type == 'seller'){{$seller->first_name .' '. $seller->last_name}} @else {{ app('general_setting')->company_name }} @endif</strong></p>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                @php
                                    $sellerShipping = $shippingMethods->where('request_by_user', $key);
                                @endphp
                                <select class="primary_select shipping_method" name="shipping_method[]" data-id="{{$key}}" name="product">
                                    @foreach($sellerShipping as $key => $shipping_method)
                                    <option value="{{$shipping_method->id}}" {{$package_wise_shipping['shipping_id'] == $shipping_method->id?'selected':''}}>{{$shipping_method->method_name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="shipping_cost[]" value="{{$package_wise_shipping['shipping_cost']}}">
                            </div>
                        </div>
                    </div>
                    @php
                        $shipment_time = $package_wise_shipping['shipping_time'];
                        $shipment_time = explode(" ", $shipment_time);
                        $dayOrOur = $shipment_time[1];
                        $shipment_time = explode("-", $shipment_time[0]);
                        $start_ = $shipment_time[0];
                        $end_ = $shipment_time[1];
                        $date = date('d-m-Y');
                        $start_date = date('d M', strtotime($date. '+ '.$start_.' '.$dayOrOur));
                        $end_date = date('d M', strtotime($date. '+ '.$end_.' '.$dayOrOur));
                    @endphp
                    <input type="hidden" name="delivery_date[]" value="@if($dayOrOur == 'days' || $dayOrOur == 'Days' ||$dayOrOur == 'Day'){{__('shipping.estimated_arrival_date')}}: {{$start_date}} - {{$end_date}}@elseif($dayOrOur == 'hrs' || $dayOrOur == 'Hrs'){{__('shipping.estimated_arrival_time')}}: {{$start_date}} - {{$end_date}}@else @endif">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table ">
                                        <div class="overflow-auto min-height-280">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">{{ __('product.product_name') }}</th>
                                                        <th width="20%">{{ __('product.variation') }}</th>
                                                        <th width="10%">{{ __('common.price') }}</th>
                                                        <th width="20%">{{ __('common.qty') }}</th>
                                                        <th width="5%">{{ __('common.total_price') }}</th>
                                                        <th width="5%">{{ __('common.remove') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $packagewiseTax = 0;
                                                    @endphp
                                                    @foreach($cartItems as $key => $item)
                                                    <tr>
                                                        <td class="text-nowrap">
                                                            {{$item->product->product->product->product_name}}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @if($item->product->product->product->product_type == 2)
                                                            @foreach($item->product->product_variations as $key => $combination)
                                                            @if($combination->attribute->id == 1)
                                                            <p>{{$combination->attribute->name}}: {{$combination->attribute_value->color->name}}</p>
                                                            @else
                                                            <p>{{$combination->attribute->name}}: {{$combination->attribute_value->value}}</p>
                                                            @endif
                                                            @endforeach
                                                            @else
                                                            <p>{{__('product.single_product')}}</p>
                                                            @endif
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @if (file_exists(base_path().'/Modules/GST/'))
                                                                @if (session()->has('inhouse_order_shipping_address') && app('gst_config')['enable_gst'] == "gst")
                                                                    @php
                                                                        if (session()->get('inhouse_order_shipping_address')['is_bill_address'] == 0) {
                                                                            $billing_address = session()->get('inhouse_order_shipping_address');
                                                                            $billing_state = session()->get('inhouse_order_shipping_address')['shipping_state'];
                                                                        }else {
                                                                            $billing_address = session()->get('inhouse_order_billing_address');
                                                                            $billing_address = session()->get('inhouse_order_billing_address')['billing_state'];
                                                                        }
                                                                        $shipping_address = session()->get('inhouse_order_shipping_address');
                                                                    @endphp
                                                                    @php
                                                                        $seller_state = \app\Traits\PickupLocation::pickupPointAddress($seller->id)->state_id;
                                                                    @endphp
                                                                    @if ($seller_state == $shipping_address['shipping_state'])
                                                                        @if($item->product->product->product->gstGroup)
                                                                            @php
                                                                                $sameStateTaxesGroup = json_decode($item->product->product->product->gstGroup->same_state_gst);
                                                                                $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                                                                            @endphp
                                                                            @foreach ($sameStateTaxesGroup as $key => $sameStateTax)
                                                                                @php
                                                                                    $gstAmount = $item->total_price * $sameStateTax / 100;
                                                                                    $gstAmountTotal += $gstAmount;
                                                                                    $packagewiseTax += $gstAmount;
                                                                                @endphp

                                                                            @endforeach
                                                                        @else
                                                                            @php
                                                                                $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
                                                                            @endphp
                                                                            @foreach ($sameStateTaxes as $key => $sameStateTax)
                                                                                @php
                                                                                    $gstAmount = $item->total_price * $sameStateTax->tax_percentage / 100;
                                                                                    $gstAmountTotal += $gstAmount;
                                                                                    $packagewiseTax += $gstAmount;
                                                                                @endphp
                                                                            @endforeach
                                                                        @endif
                                                                    @else
                                                                        @if($item->product->product->product->gstGroup)
                                                                            @php
                                                                                $diffStateTaxesGroup = json_decode($item->product->product->product->gstGroup->outsite_state_gst);
                                                                                $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                                                                            @endphp
                                                                            @foreach ($diffStateTaxesGroup as $key => $diffStateTax)
                                                                                @php
                                                                                    $gstAmount = $item->total_price * $diffStateTax / 100;
                                                                                    $gstAmountTotal += $gstAmount;
                                                                                    $packagewiseTax += $gstAmount;
                                                                                @endphp
                                                                            @endforeach
                                                                        @else
                                                                            @php
                                                                                $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
                                                                            @endphp
                                                                            @foreach ($diffStateTaxes as $key => $diffStateTax)
                                                                                @php
                                                                                    $gstAmount = $item->total_price * $diffStateTax->tax_percentage / 100;
                                                                                    $gstAmountTotal += $gstAmount;
                                                                                    $packagewiseTax += $gstAmount;
                                                                                @endphp

                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                @elseif(app('gst_config')['enable_gst'] == "flat_tax")
                                                                    @if($item->product->product->product->gstGroup)
                                                                        @php
                                                                            $flatTaxGroup = json_decode($item->product->product->product->gstGroup->same_state_gst);
                                                                            $flatTaxGroup = (array) $flatTaxGroup;
                                                                        @endphp
                                                                        @foreach($flatTaxGroup as $sameStateTax)
                                                                            @php
                                                                                $gstAmount = $item->total_price * $sameStateTax / 100;
                                                                                $gstAmountTotal += $gstAmount;
                                                                                $packagewiseTax += $gstAmount;
                                                                            @endphp
                                                                        @endforeach
                                                                    @else
                                                                        @php
                                                                            $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
                                                                            $gstAmount = $item->total_price * $flatTax->tax_percentage / 100;
                                                                            $gstAmountTotal += $gstAmount;
                                                                            $packagewiseTax += $gstAmount;
                                                                        @endphp
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            @php
                                                                $product = \Modules\Seller\Entities\SellerProductSKU::where('id',$item->product_id)->first();
                                                                $totalItem += $item->qty;
                                                                $subtotal += $product->selling_price * $item->qty;
                                                                $actualtotal += $item->total_price;
                                                            @endphp
                                                            {{single_price($item->price)}}
                                                            @if($item->price != $product->selling_price)
                                                            <del>{{single_price($product->selling_price)}}</del>
                                                            @endif
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <input class="primary_input_field product_qty" data-id="{{$item->id}}" type="number" step="1" min="1" name=""
                                                                autocomplete="off" value="{{$item->qty}}">
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <p>{{single_price($item->total_price)}}</p>
                                                        </td>
                                                        <td class="text-nowrap"><a href="" data-id="{{$item->id}}" class="deleteCartItem"><i class="ti-trash"></i></a></td>
                                                    </tr>
                                                    @endforeach
                                                    <input type="hidden" name="packagewiseTax[]" value="{{$packagewiseTax}}">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
    @else
    <p class="no_product">{{ __('order.no_product_selected') }}</p>
    @endif
</div>
<input type="hidden" name="number_of_package" value="{{$items}}">
<div class="col-lg-4 mb-50">
    <table class="table-borderless clone_line_table">
        <tr>
            <div class="main-title d-flex">
                <h3 class="mb-3 mr-30">{{ __('common.summary') }}</h3>
            </div>
        </tr>
        <tr>
            <td><strong class="pr-2">{{ __('order.total_quantity') }}</strong></td>
            <td>: <strong class="pl-4">{{getNumberTranslate($totalItem)}}</strong></td>
            <input type="hidden" value="{{$totalItem}}" name="total_quantity">
        </tr>
        <tr>
            <td><strong class="pr-2">{{ __('order.sub_total') }}</strong></td>
            <td>: <strong class="pl-4">{{single_price($subtotal)}}</strong></td>
            <input type="hidden" value="{{$subtotal}}" name="sub_total">
        </tr>
        <tr>
            <td><strong class="pr-2">{{ __('common.discount') }}</strong></td>
            <td>: <strong class="pl-4">- {{single_price($subtotal - $actualtotal)}}</strong></td>
            <input type="hidden" value="{{$subtotal - $actualtotal}}" name="discount_total">
        </tr>
        <tr>
            <td><strong class="pr-2">{{ __('order.total_shipping_charge') }}</strong></td>
            <td>: <strong class="pl-4">{{single_price($shippingtotal)}}</strong></td>
            <input type="hidden" value="{{$shippingtotal}}" name="shipping_charge">
        </tr>
        <tr>
            <td><strong class="pr-2">{{ __('Total TAX/GST') }}</strong></td>
            <td>: <strong class="pl-4">{{single_price($gstAmountTotal)}}</strong></td>
            <input type="hidden" value="{{$gstAmountTotal}}" name="gst_tax_total">
        </tr>
        <tr>
            <td><strong class="pr-2">{{ __('common.grand_total') }}</strong></td>
            <td>: <strong class="pl-4">{{single_price($actualtotal + $shippingtotal  + $gstAmountTotal)}}</strong></td>
            <input type="hidden" value="{{$actualtotal + $shippingtotal  + $gstAmountTotal}}" name="grand_total">
        </tr>
    </table>
</div>
@if(session()->has('inhouse_order_shipping_address'))
<div class="col-lg-6 offset-lg-3 mt-50">
    <div class="col-lg-12 text-center">
        <button id="submit_btn" type="submit" class="primary-btn fix-gr-bg"
            data-toggle="tooltip" title="" data-original-title="">
            <span class="ti-check"></span>
            {{ __('order.create_order') }} </button>
    </div>
</div>
@endif
