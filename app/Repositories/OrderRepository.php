<?php

namespace App\Repositories;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Traits\Carrier;
use App\Models\OrderPayment;
use App\Traits\Notification;
use App\Traits\PickupLocation;
use App\Models\GuestOrderDetail;
use App\Traits\GoogleAnalytics4;
use App\Models\OrderAddressDetail;
use App\Models\OrderPackageDetail;
use App\Models\OrderProductDetail;
use Illuminate\Support\Facades\DB;
use App\Models\DigitalFileDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\Marketing\Entities\Coupon;
use Illuminate\Support\Facades\Session;
use Modules\GiftCard\Entities\GiftCard;
use Modules\Wallet\Entities\BankPayment;
use Modules\Marketing\Entities\CouponUse;
use Modules\Marketing\Entities\ReferralUse;
use Modules\Marketing\Entities\ReferralCode;
use Modules\Affiliate\Events\ReferralPayment;
use Modules\Seller\Entities\SellerProductSKU;
use \Modules\Wallet\Repositories\WalletRepository;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\GeneralSetting\Entities\NotificationSetting;
use Modules\OrderManage\Repositories\OrderManageRepository;
use Modules\Shipping\Entities\PickupLocation as EntitiesPickupLocation;
use Modules\Shipping\Http\Controllers\OrderSyncWithCarrierController;

class OrderRepository
{
    use GoogleAnalytics4, PickupLocation, Notification;
    public function myPurchaseOrderList()
    {
        return Order::with('customer', 'packages', 'packages.products')->where('customer_id', auth()->user()->id)->latest()->paginate(5, ['*'], 'myPurchaseOrderList');
    }

    public function myPurchaseOrderListwithRN($data)
    {
        return Order::with('customer', 'packages', 'packages.products')->where('customer_id', auth()->user()->id)->latest()->paginate($data, ['*'], 'myPurchaseOrderList');
    }

    public function myPurchaseOrderListNotPaid()
    {
        return Order::with('customer', 'packages', 'packages.products')->where('customer_id', auth()->user()->id)->where('is_paid', 0)->latest()->paginate(8, ['*'], 'myPurchaseOrderListNotPaid');
    }

    public function myPurchaseOrderPackageListRecieved()
    {
        $customer_id = auth()->user()->id;
        return OrderPackageDetail::whereHas('order', function ($q) use ($customer_id) {
            $q->where('customer_id', $customer_id);
        })->where('delivery_status', 3)->latest()->paginate(8, ['*'], 'toRecievedList');
    }

    public function myPurchaseOrderPackageListShipped()
    {
        $customer_id = auth()->user()->id;
        return OrderPackageDetail::whereHas('order', function ($q) use ($customer_id) {
            $q->where('customer_id', $customer_id);
        })->where('delivery_status', 2)->latest()->paginate(8, ['*'], 'toShipped');
    }

    public function orderFindByID($id)
    {
        return Order::with('customer', 'packages', 'packages.seller', 'packages.delivery_states', 'shipping_address', 'billing_address', 'packages.products', 'packages.products.seller_product_sku.product_variations')->findOrFail($id);
    }

    public function changeReceiveStatusByCustomer($request)
    {
        $order = Order::findOrFail($request['order_id']);
        $order->is_received = 1;
        $order->order_status = 5;
        $order->is_completed = 1;
        $order->save();

        $package = OrderPackageDetail::findOrFail($request['package_id']);
        $package->delivery_status = 5;
        $package->save();

        return true;
    }

    public function orderFindByOrderID($order_id)
    {
        return Order::with('customer', 'packages', 'packages.seller', 'packages.delivery_states', 'shipping_address', 'billing_address', 'packages.products', 'packages.products.seller_product_sku.product_variations')->where('order_number',$order_id)->first();
    }

    public function orderPackageFindByID($id)
    {
        return OrderPackageDetail::findOrFail($id);
    }

    public function orderFindByOrderNumber($data, $user = null)
    {
        $order = null;
        if ($user != null) {
            $order = Order::with('customer', 'packages.seller', 'packages.delivery_states', 'shipping_address', 'billing_address', 'packages.products.seller_product_sku.product.product', 'packages.products.seller_product_sku.product_variations.attribute', 'packages.products.seller_product_sku.product_variations.attribute_value.color', 'packages.products.seller_product_sku.sku')
                ->where('order_number', $data['order_number'])->where('customer_id', $user->id)->orWhere('order_number', $data['order_number'])->where('customer_id', null)
                ->first();
        } else {
            $order = Order::with('customer', 'packages.seller', 'packages.delivery_states', 'shipping_address', 'billing_address', 'packages.products.seller_product_sku.product.product', 'packages.products.seller_product_sku.product_variations.attribute', 'packages.products.seller_product_sku.product_variations.attribute_value.color', 'packages.products.seller_product_sku.sku')
                ->where('order_number', $data['order_number'])->where('customer_id', null)
                ->first();
        }

        if ($order) {
            if ($order->customer_id) {
                return $order;
            } else {

                if (app('general_setting')->track_order_by_secret_id) {
                    if (isset($data['secret_id']) && $order->guest_info->guest_id == $data['secret_id']) {
                        return $order;
                    }elseif(auth()->check()){
                        return $order;
                    }
                     else {
                        return "Invalid Secret ID";
                    }
                } else {
                    return $order;
                }
            }
        } else {
            return "Invalid Tracking ID";
        }
    }

    public function orderStore($data)
    {

        $checkoutRepo = new CheckoutRepository();
        $query = $data['carts'];
        $destroy_ids = [];
        $club_point = 0;

        if (auth()->check()) {
            $customer_email = $checkoutRepo->activeShippingAddress()->email;
            $customer_phone = $checkoutRepo->activeShippingAddress()->phone;
        } else {
            $customer_email = $checkoutRepo->activeShippingAddress()->email;
            $customer_phone = $checkoutRepo->activeShippingAddress()->phone;
        }

        $productList = $query;

        if(isModuleActive('ClubPoint')){
            foreach ($productList as $key => $product_card) {
                if(isModuleActive('MultiVendor')){
                    foreach ($product_card as $key => $product) {

                        if( $product->product_type != "gift_card"){
                            $club_point += $product->product->product->product->club_point * $product->qty;
                        }
                    }
                }else{
                    if( $product_card->product_type != "gift_card"){
                        $club_point += $product_card->product->product->product->club_point * $product_card->qty;
                    }
                }
            }
        }
        $billing_address = null;
        $shipping_address = null;
        if(auth()->check()){
            $shipping_address = $checkoutRepo->activeShippingAddress();
            if($checkoutRepo->activeBillingAddress() == null){
                $billing_address = $shipping_address;
            }else{
                $billing_address = $checkoutRepo->activeBillingAddress();
            }
        }
        $package_wise_shipping = session()->get('package_wise_shipping');
        if(isModuleActive('MultiVendor')){
            if(isModuleActive('INTShipping')){
                $shipping_cost = collect($data['shipping_cost'])->sum();
            }else{
                $shipping_cost = collect($package_wise_shipping)->sum('shipping_cost');
            }
        }else{
            $shipping_cost = $data['shipping_cost'];
        }
        $delivery_type = 'home_delivery';
        $pickup_location = null;
        if(!isModuleActive('MultiVendor') && session()->has('delivery_info')){
            $delivery_info = session()->get('delivery_info');
            $delivery_type = $delivery_info['delivery_type'];
            if($delivery_type == 'pickup_location'){
                $pickup_location = EntitiesPickupLocation::find(base64_decode($delivery_info['pickup_location']));
            }
        }
        $order = new Order();
        $order->customer_id =(auth()->check()) ? auth()->user()->id : null;
        if(isModuleActive('ClubPoint')){
            $order->club_point = $club_point;
        }

        $orderDate = date("Ymd");
        $totalOrder =  Order::count() + 1;
        $nextOrder = str_pad( $totalOrder, 6, "0", STR_PAD_LEFT );
        $orderNumber = $orderDate.$nextOrder;
        $order->order_number =  $orderNumber;
        $order->payment_type = $data['payment_method'];
        $order->is_paid = ($data['payment_method'] != 1 && $data['payment_method'] != 7) ? 1 : 0;
        $order->customer_email = $customer_email;
        $order->customer_phone = $customer_phone;
        $order->customer_shipping_address = @$shipping_address->id;
        $order->customer_billing_address = @$billing_address->id;

        if(isModuleActive('AuctionProducts') && session()->has('auction_type')){
                $order->grand_total = $data['grand_total'];
                $order->sub_total = session()->get('auction_price');
                $order->discount_total = 0;
        }else{
            $order->grand_total = $data['grand_total'];
            $order->sub_total = $data['sub_total'];
            $order->discount_total = $data['discount_total'];
        }

        $order->shipping_total = $shipping_cost;
        $order->number_of_package = $data['number_of_package'];
        $order->number_of_item = $data['number_of_item'];
        $order->order_status = 0;
        $order->order_payment_id = ($data['order_payment_id'] != 0) ? $data['order_payment_id'] : null;
        $order->tax_amount = $data['tax_total'];
        $order->note = session()->has('order_note')?session()->get('order_note'):null;
        $order->delivery_type = $delivery_type;
        $order->pickup_location_id = $pickup_location?$pickup_location->id:null;
        $order->save();
        if (!auth()->check()) {
            $shipping_address = (object) session()->get('shipping_address');
            if(session()->has('billing_address')){
                $billing_address = (object) session()->get('billing_address');
            }else{
                $billing_address = $shipping_address;
            }
            $is_pickup = 0;
            if($delivery_type == 'pickup_location' && $pickup_location){
                $is_pickup = 1;
            }
            $address = GuestOrderDetail::create([
                'order_id' => $order->id,
                'guest_id' => $order->id . '-' . date('ymd-his'),
                'billing_name' => $billing_address->name,
                'billing_email' => $billing_address->email,
                'billing_phone' => $billing_address->phone,
                'billing_address' => $billing_address->address,
                'billing_city_id' => $billing_address->city,
                'billing_state_id' => $billing_address->state,
                'billing_country_id' => $billing_address->country,
                'billing_post_code' => $billing_address->postal_code,
                'shipping_name' => $is_pickup?$pickup_location->pickup_location:$shipping_address->name,
                'shipping_email' => $shipping_address->email,
                'shipping_phone' => $shipping_address->phone,
                'shipping_address' => $is_pickup?$pickup_location->address:$shipping_address->address,
                'shipping_city_id' => $is_pickup?$pickup_location->city_id:$shipping_address->city,
                'shipping_state_id' => $is_pickup?$pickup_location->state_id:$shipping_address->state,
                'shipping_country_id' => $is_pickup?$pickup_location->country_id:$shipping_address->country,
                'shipping_post_code' => $is_pickup?$pickup_location->pin_code:$shipping_address->postal_code
            ]);
        }else{
            $is_same_billing = 1;

            if($order->customer_shipping_address != $order->customer_billing_address){
                $is_same_billing = 0;
            }

            $is_pickup = 0;
            if($delivery_type == 'pickup_location' && $pickup_location){
                $is_pickup = 1;
            }

            $address = OrderAddressDetail::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'shipping_name' => $is_pickup?$pickup_location->pickup_location:$shipping_address->name,
                'shipping_email' => $shipping_address->email,
                'shipping_phone' => $shipping_address->phone,
                'shipping_address' => $is_pickup?$pickup_location->address:$shipping_address->address,
                'shipping_country_id' => $is_pickup?$pickup_location->country_id:$shipping_address->country,
                'shipping_state_id' => $is_pickup?$pickup_location->state_id:$shipping_address->state,
                'shipping_city_id' => $is_pickup?$pickup_location->city_id:$shipping_address->city,
                'shipping_postcode' => $is_pickup?$pickup_location->pin_code:$shipping_address->postal_code,
                'bill_to_same_address' => $is_same_billing,
                'billing_name' => $billing_address->name,
                'billing_email' => $billing_address->email,
                'billing_phone' => $billing_address->phone,
                'billing_address' => $billing_address->address,
                'billing_country_id' => $billing_address->country,
                'billing_state_id' => $billing_address->state,
                'billing_city_id' => $billing_address->city,
                'billing_postcode' => $billing_address->postal_code,
            ]);
        }


        $val = 0;
        $e_items = [];
        $seller_id = null;
        if(isModuleActive('MultiVendor')){
            foreach ($productList as $key => $products) {
                $seller_id = $key;
                $seller = User::find($seller_id);
                $index_no = $val + 1;
                $package = OrderPackageDetail::create([
                    'order_id' => $order->id,
                    'seller_id' => $seller_id,
                    'package_code' => date('ymdhsi').rand(11,99),
                    'number_of_product' => count($products),
                    'shipping_cost' => isModuleActive('INTShipping')?$data['shipping_cost'][$val]:$package_wise_shipping[$seller_id]['shipping_cost'],
                    'shipping_date' => $data['delivery_date'][$val],
                    'shipping_method' => $package_wise_shipping[$seller_id]['shipping_id'],
                    'carrier_id' => Carrier::carrierId($package_wise_shipping[$seller_id]['shipping_id']),
                    'pickup_point_id' => PickupLocation::pickupPoint($seller_id),
                    'tax_amount' => $data['packagewiseTax'][$val],
                    'delivery_status' => 1,
                    'weight' => $package_wise_shipping[$seller_id]['totalItemWeight'],
                    'height' => $package_wise_shipping[$seller_id]['totalItemHeight'],
                    'length' => $package_wise_shipping[$seller_id]['totalItemLength'],
                    'breadth' => $package_wise_shipping[$seller_id]['totalItemBreadth'],
                    'is_paid' => ($data['payment_method'] != 1 && $data['payment_method'] != 7) ? 1 : 0

                ]);
                $val++;
                foreach ($products as $key => $product) {
                    if ($product->product_type == "gift_card") {
                        $seller_product = GiftCard::findOrFail($product->product_id);
                        $tax= 0;
                        if (isModuleActive('Affiliate') ) {
                            //if guest checkout process guest variable will be true otherwise false.
                            if(auth()->check() && auth()->user()->isReferralUser){
                                Event::dispatch(new ReferralPayment(auth()->id(),$product->giftCard->id,$product->total_price));
                            }else{
                                //if guest checkout process user_id will be 0
                                Event::dispatch(new ReferralPayment(0,$product->giftCard->id,$product->total_price));
                            }
                        }
                        $e_items[]=[
                            "item_id"=> $product->giftCard->id,
                            "item_name"=> $product->giftCard->name,
                            "currency"=> currencyCode(),
                            "price"=> $product->price
                        ];
                    } else {
                        $seller_product = SellerProductSKU::findOrFail($product->product_id)->product;
                        $seller_product->update([
                            'total_sale' => $seller_product->total_sale + $product->qty
                        ]);
                        if ($seller_product->product->category_id != null || $seller_product->product->category_id != 0) {
                            $category = $seller_product->product->category;
                            $seller_product->product->category->update(['total_sale' => $category->total_sale + $product->qty]);
                        }
                        if ($seller_product->product->brand_id != null || $seller_product->product->brand_id != 0) {
                            $brand = $seller_product->product->brand;
                            $seller_product->product->brand->update(['total_sale' => $brand->total_sale + $product->qty]);
                        }
                        $address_state = $address->shipping_state_id;
                        $tax = $this->getProductTax($product, $seller, $address_state);
                        if (isModuleActive('Affiliate') ) {
                            //if guest checkout process guest variable will be true otherwise false.
                            if(auth()->check() && auth()->user()->isReferralUser){
                                Event::dispatch(new ReferralPayment(auth()->id(),$product->product->product->id,$product->total_price));
                            }else{
                                //if guest checkout process user_id will be 0
                                Event::dispatch(new ReferralPayment(0,$product->product->product->id,$product->total_price));
                            }
                        }
                        if(isModuleActive('AuctionProducts') && session()->has('auction_type')){
                                $e_items[]=[
                                    "item_id"=> $product->product->sku->sku,
                                    "item_name"=> $product->product->product->product_name,
                                    "currency"=> currencyCode(),
                                    "price"=> session()->get('auction_price')
                                ];
                        }else{
                            $e_items[]=[
                                "item_id"=> $product->product->sku->sku,
                                "item_name"=> $product->product->product->product_name,
                                "currency"=> currencyCode(),
                                "price"=> $product->price
                            ];
                        }

                        $product_info = DB::table('products')->where('id',$product->product_id)->first(); //check if is it a digital product
                        if(!empty($product_info) && $product_info->is_physical == 0 && Auth::check()){
                            $payment_method = PaymentMethod::where('id',$data['payment_method'])->first();
                            if(strtolower('Cash On Delivery') != strtolower($payment_method->name)){
                                DigitalFileDownload::create([  //Create Download able file
                                    "customer_id" => Auth::id(),
                                    "seller_id" => $seller_id,
                                    "order_id" => $order->id,
                                    "package_id" => $package->id,
                                    "seller_product_sku_id" => $product->product_id,
                                    "product_sku_id" => $product->product_id,
                                    "download_limit" => 3,
                                    "downloaded_count" => 0
                                ]);
                            }
                        }

                    }

                    if(isModuleActive('AuctionProducts') && session()->has('auction_type')){
                            OrderProductDetail::create([
                                'package_id' => $package->id,
                                'type' => $product->product_type,
                                'product_sku_id' => $product->product_id,
                                'qty' => $product->qty,
                                'price' => session()->get('auction_price'),
                                'total_price' => session()->get('auction_price'),
                                'tax_amount' => $tax
                            ]);
                    }else{
                        OrderProductDetail::create([
                            'package_id' => $package->id,
                            'type' => $product->product_type,
                            'product_sku_id' => $product->product_id,
                            'qty' => $product->qty,
                            'price' => $product->price,
                            'total_price' => $product->total_price,
                            'tax_amount' => $tax
                        ]);
                    }
                    $destroy_ids[] = $product->id;
                }

                //start order sync with carrier
                $syncController = new OrderSyncWithCarrierController();
                $syncController->OrderSyncWithCarrier($package);
                //end order sync with carrier
            }
        }
        //for single vendor
        else{

            $single_package_height_weight_info = session()->get('single_package_height_weight_info');
            $package = OrderPackageDetail::create([
                'order_id' => $order->id,
                'seller_id' => 1,
                'package_code' => date('ymdhsi').rand(11,99),
                'number_of_product' => count($productList),
                'shipping_cost' => $data['shipping_cost'],
                'shipping_date' => $data['delivery_date'],
                'shipping_method' => $data['shipping_method'],
                'carrier_id' => Carrier::carrierId($data['shipping_method']),
                'pickup_point_id' => PickupLocation::pickupPoint(1),
                'tax_amount' => $data['tax_total'],
                'delivery_status' => 1,
                 'weight' => $single_package_height_weight_info['totalItemWeight'],
                'height' => $single_package_height_weight_info['totalItemHeight'],
                'length' => $single_package_height_weight_info['totalItemLength'],
                'breadth' => $single_package_height_weight_info['totalItemBreadth'],
                'is_paid' => ($data['payment_method'] != 1 && $data['payment_method'] != 7) ? 1 : 0,
            ]);

            foreach($productList as $key => $product){

                if($product->product_type == 'product'){


                    $address_state = $address->shipping_state_id;
                    $seller = User::find(1);
                    $tax = $this->getProductTax($product, $seller, $address_state);
                    OrderProductDetail::create([
                        'package_id' => $package->id,
                        'type' => $product->product_type,
                        'product_sku_id' => $product->product_id,
                        'qty' => $product->qty,
                        'price' => $product->price,
                        'total_price' => $product->total_price,
                        'tax_amount' => $tax
                    ]);
                    $e_items[]=[
                        "item_id"=> $product->product->sku->sku,
                        "item_name"=> $product->product->product->product_name,
                        "currency"=> currencyCode(),
                        "price"=> $product->price
                    ];

                    $product_info = DB::table('products')->where('id',$product->product_id)->first(); //check if is it a digital product
                    if(!empty($product_info) &&  $product_info->is_physical == 0 && Auth::check()){
                        $payment_method = PaymentMethod::where('id',$data['payment_method'])->first();
                        if(strtolower('Cash On Delivery') != strtolower($payment_method->name)){
                            DigitalFileDownload::create([  //Create Download able file
                                "customer_id" => Auth::id(),
                                "seller_id" => 1,
                                "order_id" => $order->id,
                                "package_id" => $package->id,
                                "seller_product_sku_id" => $product->product_id,
                                "product_sku_id" => $product->product_id,
                                "download_limit" => 3,
                                "downloaded_count" => 0
                            ]);
                        }
                    }
                }
                else{
                    OrderProductDetail::create([
                        'package_id' => $package->id,
                        'type' => $product->product_type,
                        'product_sku_id' => $product->product_id,
                        'qty' => $product->qty,
                        'price' => $product->price,
                        'total_price' => $product->total_price,
                        'tax_amount' => 0
                    ]);
                    $e_items[]=[
                        "item_id"=> $product->giftCard->sku,
                        "item_name"=> $product->giftCard->name,
                        "currency"=> currencyCode(),
                        "price"=> $product->price
                    ];
                }
                $destroy_ids[] = $product->id;



                if (isModuleActive('Affiliate') ) {
                    if($product->product_type == 'product'){
                        //if guest checkout process guest variable will be true otherwise false.
                        if(auth()->check() && auth()->user()->isReferralUser){
                            Event::dispatch(new ReferralPayment(auth()->id(),$product->product->product->id,$product->total_price));
                        }else{
                            //if guest checkout process user_id will be 0
                            Event::dispatch(new ReferralPayment(0,$product->product->product->id,$product->total_price));
                        }
                    }else{
                        //if guest checkout process guest variable will be true otherwise false.
                        if(auth()->check() && auth()->user()->isReferralUser){
                            Event::dispatch(new ReferralPayment(auth()->id(),$product->giftCard->id,$product->total_price));
                        }else{
                            //if guest checkout process user_id will be 0
                            Event::dispatch(new ReferralPayment(0,$product->giftCard->id,$product->total_price));
                        }
                    }
                }
            }

            //start order sync with carrier
            $syncController = new OrderSyncWithCarrierController();
            $syncController->OrderSyncWithCarrier($package);
            //end order sync with carrier
        }


        //ga4
        if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
            $eData = [
                'name' => 'add_payment_info',
                'params' => [
                    "currency" => currencyCode(),
                    "value"=> 1,
                    "payment_type"=> $data['payment_method'],
                    "items" => $e_items,
                ],
            ];
            $this->postEvent($eData);
        }
        //end ga4

        Cart::destroy($destroy_ids);

        // ga4
        $ga_transaction_id = 0;

        if ($data['payment_method'] == 1) {
            $order_payment = $this->orderPaymentDone($data['grand_total'], 1, "none", (auth()->check()) ? auth()->user() : null);
            $order->update([
                'order_payment_id' => $order_payment->id
            ]);
            // ga4
            $ga_transaction_id = $order_payment->id;
        }

        if ($data['payment_method'] == 2) {
            $user_type = (auth()->check()) ? "registered" : "guest";
            $customer_id = ($order->customer_id) ? $order->customer_id : $order->guest_info->id;
            $wallet_service = new WalletRepository;
            $wallet_service->cartPaymentData($order->id, $data['grand_total'], "Cart Payment", $customer_id, $user_type);
            $order_payment = $this->orderPaymentDone($data['grand_total'], 2, "none", (auth()->check()) ? auth()->user() : null);
            $order->update([
                'order_payment_id' => $order_payment->id
            ]);
            // ga4
            $ga_transaction_id = $order_payment->id;
        }


        if ($data['payment_method'] == 7) {
            $bank_details = BankPayment::find(session()->get("bank_detail_id"));
            $bank_details->itemable_id = decrypt($data['payment_id']);
            $bank_details->itemable_type = "App\Models\OrderPayment";
            $bank_details->save();
            session()->forget("bank_detail_id");
        }

        //ga4
        if(app('business_settings')->where('type', 'google_analytics')->first()->status == 1){
            $ga_transaction_id = $data['payment_id'];
            $eData = [
                'name' => 'purchase',
                'params' => [
                    "currency" => currencyCode(),
                    "value"=> 12.21,
                    "transaction_id"=> $ga_transaction_id,
                    "items" => $e_items,
                ],
            ];
            $this->postEvent($eData);
        }
        //end ga4

        if (auth()->check()) {
            $referral_code = ReferralCode::where('user_id', auth()->user()->id)->first();
            if (!isset($referral_code)) {
                ReferralCode::create([
                    'user_id' => auth()->user()->id,
                    'referral_code' => auth()->user()->id . rand(111111111, 999999999),
                    'status' => 1
                ]);
            }
            $referral_use = ReferralUse::where('user_id', auth()->user()->id)->where('is_use', 0)->first();
            if (isset($referral_use)) {
            }

            if (isset($data['coupon_id'])) {
                $coupon = Coupon::findOrFail($data['coupon_id']);
                $couponUse = CouponUse::where('user_id', auth()->user()->id)->where('coupon_id', $data['coupon_id'])->first();
                if (isset($couponUse)) {
                    if ($coupon->is_multiple_buy == 1) {
                        CouponUse::create([
                            'user_id' => auth()->user()->id,
                            'coupon_id' => $data['coupon_id'],
                            'order_id' => $order->id,
                            'discount_amount' => $data['coupon_amount'],
                        ]);
                        session()->forget('coupon_type');
                        session()->forget('coupon_discount');
                        session()->forget('coupon_discount_type');
                        session()->forget('maximum_discount');
                        session()->forget('maximum_products');
                        session()->forget('coupon_id');
                    }
                } else {
                    CouponUse::create([
                        'user_id' => auth()->user()->id,
                        'coupon_id' => $data['coupon_id'],
                        'order_id' => $order->id,
                        'discount_amount' => $data['coupon_amount'],
                    ]);
                    session()->forget('coupon_type');
                    session()->forget('coupon_discount');
                    session()->forget('coupon_discount_type');
                    session()->forget('maximum_discount');
                    session()->forget('maximum_products');
                    session()->forget('coupon_id');
                }
            }
        }

        //shipping carrier config
        if(sellerWiseShippingConfig(1)['order_confirm_and_sync'] == 'Automatic'){
            $orderManageRepo = new OrderManageRepository();
            $orderManageRepo->orderConfirm($order->id);
        }

        // send Notification for create order
        $notificationUrl = route('frontend.my_purchase_order_detail',encrypt($order->id));
        $notificationUrl = str_replace(url('/'),'',$notificationUrl);

        $this->notificationUrl = $notificationUrl;
        $this->adminNotificationUrl = 'ordermanage/total-sales-list';
        $this->routeCheck = 'order_manage.total_sales_index';
        $this->typeId = EmailTemplateType::where('type','order_email_template')->first()->id;//order email templete type id
        $this->order_on_notification = $order;
        $notification = NotificationSetting::where('slug','new-order')->first();
        if ($notification) {
            $this->notificationSend($notification->id, $order->customer_id);
        }
        //end shipping carrier
        session()->forget('coupon_type');
        session()->forget('coupon_discount');
        session()->forget('coupon_discount_type');
        session()->forget('maximum_discount');
        session()->forget('maximum_products');
        session()->forget('coupon_id');
        session()->forget('order_note');
        session()->forget('billing_address');
        session()->forget('package_wise_shipping');
        session()->forget('single_package_height_weight_info');
        session()->forget('delivery_info');

        if(isModuleActive('AuctionProducts') && session()->has('auction_type')){
                session()->forget('auction_type');
                session()->forget('auction_price');
        }
        return $order;
    }

    public function getProductTax($product, $seller, $customer_state){
        $tax = 0;
        $sameStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['within_a_single_state'])->get();
        $diffStateTaxes = \Modules\GST\Entities\GstTax::whereIn('id', app('gst_config')['between_two_different_states_or_a_state_and_a_Union_Territory'])->get();
        $flatTax = \Modules\GST\Entities\GstTax::where('id', app('gst_config')['flat_tax_id'])->first();
        if(file_exists(base_path().'/Modules/GST/') && $product->product->product->product->is_physical == 1){

            if(app('gst_config')['enable_gst'] == "gst"){
                $state_id = PickupLocation::pickupPointAddress($seller->id)->state_id;

                if($state_id == $customer_state){
                    if($product->product->product->product->gstGroup){
                        $sameStateTaxesGroup = json_decode($product->product->product->product->gstGroup->same_state_gst);
                        $sameStateTaxesGroup = (array) $sameStateTaxesGroup;

                        foreach($sameStateTaxesGroup as $key => $sameStateTax){
                            $gstAmount = ($product->total_price * $sameStateTax) / 100;
                            $tax += $gstAmount;
                        }
                    }else{

                        foreach($sameStateTaxes as $key => $sameStateTax){
                            $gstAmount = ($product->total_price * $sameStateTax->tax_percentage) / 100;
                            $tax += $gstAmount;
                        }
                    }
                }
                else{
                    if($product->product->product->product->gstGroup){
                        $diffStateTaxesGroup = json_decode($product->product->product->product->gstGroup->outsite_state_gst);
                        $diffStateTaxesGroup = (array) $diffStateTaxesGroup;
                        foreach ($diffStateTaxesGroup as $key => $diffStateTax){
                            $gstAmount = ($product->total_price * $diffStateTax) / 100;
                            $tax += $gstAmount;
                        }
                    }else{
                        foreach ($diffStateTaxes as $key => $diffStateTax){
                            $gstAmount = ($product->total_price * $diffStateTax->tax_percentage) / 100;
                            $tax += $gstAmount;
                        }
                    }
                }
            }elseif(app('gst_config')['enable_gst'] == "flat_tax"){
                if($product->product->product->product->gstGroup){
                    $flatTaxGroup = json_decode($product->product->product->product->gstGroup->same_state_gst);
                    $flatTaxGroup = (array) $flatTaxGroup;
                    foreach($flatTaxGroup as $sameStateTax){
                        $gstAmount = $product->total_price * $sameStateTax / 100;
                        $tax += $gstAmount;
                    }
                }else{
                    $percentage = !empty($flatTax) ? $flatTax->tax_percentage:0;
                    $gstAmount = ($product->total_price * $percentage) / 100;
                    $tax += $gstAmount;
                }
            }
        }else{
            if($product->product->product->product->gstGroup){
                $sameStateTaxesGroup = json_decode($product->product->product->product->gstGroup->same_state_gst);
                $sameStateTaxesGroup = (array) $sameStateTaxesGroup;
                foreach ($sameStateTaxesGroup as $key => $sameStateTax){
                    $gstAmount = ($product->total_price * $sameStateTax) / 100;
                    $tax += $gstAmount;
                }
            }else{
                foreach ($sameStateTaxes as $key => $sameStateTax){
                    $gstAmount = ($product->total_price * $sameStateTax->tax_percentage) / 100;
                    $tax += $gstAmount;
                }
            }
        }
        return $tax;
    }


    public function orderStoreForAPI($user = null, $data)
    {
        $billing_address = null;
        $shipping_address = null;

        $delivery_type = 'home_delivery';
        $pickup_location_id = null;

        if(isset($data['delivery_type']) && $data['delivery_type'] == 'pickup_location'){
            $delivery_type = $data['delivery_type'];
            $pickup_location_id = $data['pickup_location_id'];
        }
        if ($user != null) {
            $query =  Cart::where('user_id',$user->id)->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->where('status', 1)->activeSeller();
                });
            })->orWhere('product_type', 'gift_card')->where('user_id',$user->id)->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->get();

            $recs = new \Illuminate\Database\Eloquent\Collection($query);
            $productList = $recs->groupBy('seller_id');


            $customer_email = $data['customer_email'];
            $customer_phone = $data['customer_phone'];
            $is_pickup = 0;
            $shipping_address = $user->customerShippingAddress;
            $billing_address = $user->customerBillingAddress;
            if($billing_address == null){
                $billing_address = $shipping_address;
            }

            if($delivery_type == 'pickup_location'){
                $pickup_location = EntitiesPickupLocation::find($pickup_location_id);
                $is_pickup = 1;
            }
        }
        $orderDate = date("Ymd");
        $totalOrder =  Order::count() + 1;
        $nextOrder = str_pad( $totalOrder, 6, "0", STR_PAD_LEFT );
        $orderNumber = $orderDate.$nextOrder;

        $order = Order::create([
            'customer_id' => ($user != null) ? $user->id : null,
            'order_number' => $orderNumber,
            'payment_type' => $data['payment_method'],
            'is_paid' => 0,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'customer_shipping_address' => @$shipping_address->id,
            'customer_billing_address' => @$billing_address->id,
            'grand_total' => $data['grand_total'],
            'sub_total' => $data['sub_total'],
            'discount_total' => $data['discount_total'],
            'shipping_total' => $data['shipping_total'],
            'number_of_package' => $data['number_of_package'],
            'number_of_item' => $data['number_of_item'],
            'order_status' => 0,
            'order_payment_id' => ($data['payment_id'] != 0) ? $data['payment_id'] : null,
            'tax_amount' => $data['tax_total'],
            'delivery_type' => $delivery_type,
            'pickup_location_id' => $pickup_location_id
        ]);


        $val = 0;
        foreach ($productList as $key => $products) {
            $seller_id = $key;

            $index_no = $val + 1;
            $package = OrderPackageDetail::create([
                'order_id' => $order->id,
                'seller_id' => $seller_id,
                'package_code' => date('ymdhsi').rand(11,99),
                'number_of_product' => count($products),
                'shipping_cost' => $data['shipping_cost'][$val],
                'shipping_date' => $data['delivery_date'][$val],
                'shipping_method' => $data['shipping_method'][$val],
                'carrier_id' => Carrier::carrierId($data['shipping_method'][$val]),
                'pickup_point_id' => PickupLocation::pickupPoint(1),
                'tax_amount' => isset($data['packagewiseTax'][$val]) ? $data['packagewiseTax'][$val]:0,
                'weight' => isset($data['package_wise_weight'][$val]) ? $data['package_wise_weight'][$val]:0,
                'height' => isset($data['package_wise_height'][$val]) ? $data['package_wise_height'][$val]:0,
                'length' => isset($data['package_wise_length'][$val]) ? $data['package_wise_length'][$val]:0,
                'breadth' => isset($data['package_wise_breadth'][$val]) ? $data['package_wise_breadth'][$val]:0,
                'delivery_status' => 1
            ]);
            $val++;

            if ($user != null) {

                $is_same_billing = 1;
                if($order->customer_shipping_address != $order->customer_billing_address){
                    $is_same_billing = 0;
                }

                $address = OrderAddressDetail::create([
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'shipping_name' => $is_pickup?$pickup_location->pickup_location:$shipping_address->name,
                    'shipping_email' => $shipping_address->email,
                    'shipping_phone' => $shipping_address->phone,
                    'shipping_address' => $is_pickup?$pickup_location->address:$shipping_address->address,
                    'shipping_country_id' => $is_pickup?$pickup_location->country_id:$shipping_address->country,
                    'shipping_state_id' => $is_pickup?$pickup_location->state_id:$shipping_address->state,
                    'shipping_city_id' => $is_pickup?$pickup_location->city_id:$shipping_address->city,
                    'shipping_postcode' => $is_pickup?$pickup_location->pin_code:$shipping_address->postal_code,
                    'bill_to_same_address' => $is_same_billing,
                    'billing_name' => $billing_address->name,
                    'billing_email' => $billing_address->email,
                    'billing_phone' => $billing_address->phone,
                    'billing_address' => $billing_address->address,
                    'billing_country_id' => $billing_address->country,
                    'billing_state_id' => $billing_address->state,
                    'billing_city_id' => $billing_address->city,
                    'billing_postcode' => $billing_address->postal_code,
                ]);

                foreach ($products as $key => $product) {
                    if(isset($data['product_info']) && count($data['product_info']) > 0){
                        $payload_product_info = isset($data['product_info'][$product->product_id]) ? (object)$data['product_info'][$product->product_id]:null;
                    }else{
                        $payload_product_info = null;
                    }
                    if($payload_product_info){
                        $total_price = $payload_product_info->total_price;
                        $price = $payload_product_info->price;
                    }else{
                        $total_price = $product->total_price;
                        $price = $product->price;
                    }
                    if ($product->product_type == "gift_card") {
                        $seller_product = GiftCard::find($product->product_id);
                         // affiliate
                        if (isModuleActive('Affiliate') ) {
                            Event::dispatch(new ReferralPayment(0,$product->giftCard->id,$total_price));
                        }
                        $e_items[]=[
                            "item_id"=> $product->giftCard->id,
                            "item_name"=> $product->giftCard->name,
                            "currency"=> $user->currency_code,
                            "price"=> $price
                        ];
                        $tax = 0;
                    } else {
                        $seller_product = SellerProductSKU::find($product->product_id)->product;
                        $seller_product->update([
                            'total_sale' => $seller_product->total_sale + $product->qty
                        ]);
                        if ($seller_product->product->category_id != null || $seller_product->product->category_id != 0) {
                            $category = $seller_product->product->category;
                            $seller_product->product->category->update(['total_sale' => $category->total_sale + $product->qty]);
                        }
                        if ($seller_product->product->brand_id != null || $seller_product->product->brand_id != 0) {
                            $brand = $seller_product->product->brand;
                            $seller_product->product->brand->update(['total_sale' => $brand->total_sale + $product->qty]);
                        }

                        // affiliate
                        if (isModuleActive('Affiliate') ) {
                            if($user->isReferralUser){
                                Event::dispatch(new ReferralPayment($user->id,$product->product->product->id,$total_price));
                            }else{
                                Event::dispatch(new ReferralPayment(0,$product->product->product->id,$total_price));
                            }
                        }
                        $e_items[]=[
                            "item_id"=> !empty($product->product) && !empty($product->product->sku) ? $product->product->sku->sku:'',
                            "item_name"=> $product->product->product->product_name,
                            "currency"=> $user->currency_code,
                            "price"=> $price
                        ];

                        $seller = User::find($seller_id);
                        $address_state = $address->shipping_state_id;
                        $tax = $this->getProductTax($product, $seller, $address_state);
                    }

                    OrderProductDetail::create([
                        'package_id' => $package->id,
                        'type' => $product['product_type'],
                        'product_sku_id' => $product->product_id,
                        'qty' => $product->qty,
                        'price' => $price,
                        'total_price' => $total_price,
                        'tax_amount' => $tax
                    ]);
                }
            } else {

                foreach ($products as $key => $product) {
                    if(isset($data['product_info']) && count($data['product_info']) > 0){
                        $payload_product_info = (object) $data['product_info'][$product->product_id];
                    }else{
                        $payload_product_info = null;
                    }
                    if($payload_product_info){
                        $total_price = $payload_product_info->total_price;
                        $price = $payload_product_info->price;
                    }else{
                        $total_price = $product->total_price;
                        $price = $product->price;
                    }

                    if ($product['product_type'] == "gift_card") {
                        $seller_product = GiftCard::find($product->product_id);

                        //affiliate
                        if (isModuleActive('Affiliate') ) {
                            Event::dispatch(new ReferralPayment(0,$seller_product->id,$total_price));
                        }
                    } else {
                        $seller_product = SellerProductSKU::find($product->product_id)->product;
                        $seller_product->update([
                            'total_sale' => $seller_product->total_sale + $product['qty']
                        ]);
                        if ($seller_product->product->category_id != null || $seller_product->product->category_id != 0) {
                            $category = $seller_product->product->category;
                            $seller_product->product->category->update(['total_sale' => $category->total_sale + $product->qty]);
                        }
                        if ($seller_product->product->brand_id != null || $seller_product->product->brand_id != 0) {
                            $brand = $seller_product->product->brand;
                            $seller_product->product->brand->update(['total_sale' => $brand->total_sale + $product->qty]);
                        }

                        // affiliate
                        if (isModuleActive('Affiliate') ) {
                            Event::dispatch(new ReferralPayment(0,$product->product->product->id,$total_price));
                        }
                    }

                    OrderProductDetail::create([
                        'package_id' => $package->id,
                        'type' => $product['product_type'],
                        'product_sku_id' => $product['product_id'],
                        'qty' => $product->qty,
                        'price' => $price,
                        'total_price' =>  $total_price,
                        'tax_amount' => ($product->product_type == "product") ? (tax_count($price, $product->product->product->tax, $product->product->product->tax_type) * $product->qty) : 0
                    ]);
                }

            }

             //start order sync with carrier
             if(sellerWiseShippingConfig(1)['order_confirm_and_sync'] == 'Automatic'){

                $syncController = new OrderSyncWithCarrierController();
                $syncController->OrderSyncWithCarrier($package);

             }
            //end order sync with carrier

        }
        if ($user != null) {
            $carts = Cart::where('user_id', $user->id)->where('is_select', 1)->get();
            foreach ($carts as $cart) {
                $cart->delete();
            }
        }

        if ($data['payment_method'] == 1) {

            $order_payment = $this->orderPaymentDone($data['grand_total'], 1, "none", $user);
            $order->update([
                'order_payment_id' => $order_payment->id
            ]);

        }

        if ($data['payment_method'] == 2) {
            $user_type = ($user != null) ? "registered" : "guest";
            $customer_id = ($order->customer_id) ? $order->customer_id : $order->guest_info->id;
            $wallet_service = new WalletRepository;
            $wallet_service->cartPaymentData($order->id, $data['grand_total'], "Cart Payment", $customer_id, $user_type);
            $order_payment = $this->orderPaymentDone($data['grand_total'], 2, "none", $user);
            $order->update([
                'order_payment_id' => $order_payment->id
            ]);
        }

        if ($data['payment_method'] == 7) {

            $bank_details = BankPayment::find($data['bank_details_id']);
            $bank_details->itemable_id = $data['payment_id'];
            $bank_details->itemable_type = "App\Models\OrderPayment";
            $bank_details->save();

        }

        if ($user != null) {
            $referral_code = ReferralCode::where('user_id', $user->id)->first();
            if (!isset($referral_code)) {
                ReferralCode::create([
                    'user_id' => $user->id,
                    'referral_code' => $user->id . rand(111111111, 999999999),
                    'status' => 1
                ]);
            }
            $referral_use = ReferralUse::where('user_id', $user->id)->where('is_use', 0)->first();
            if (isset($referral_use)) {

            }

            if (isset($data['coupon_id'])) {
                $coupon = Coupon::find($data['coupon_id']);
                $couponUse = CouponUse::where('user_id', $user->id)->where('coupon_id', $data['coupon_id'])->first();
                if (isset($couponUse) && $coupon) {
                    if ($coupon->is_multiple_buy == 1) {
                        CouponUse::create([
                            'user_id' => $user->id,
                            'coupon_id' => $data['coupon_id'],
                            'order_id' => $order->id,
                            'discount_amount' => $data['coupon_amount'],
                        ]);
                        session()->forget('coupon_type');
                        session()->forget('coupon_discount');
                        session()->forget('coupon_discount_type');
                        session()->forget('maximum_discount');
                        session()->forget('maximum_products');
                        session()->forget('coupon_id');
                    }
                } else {
                    CouponUse::create([
                        'user_id' => $user->id,
                        'coupon_id' => $data['coupon_id'],
                        'order_id' => $order->id,
                        'discount_amount' => $data['coupon_amount'],
                    ]);
                    session()->forget('coupon_type');
                    session()->forget('coupon_discount');
                    session()->forget('coupon_discount_type');
                    session()->forget('maximum_discount');
                    session()->forget('maximum_products');
                    session()->forget('coupon_id');
                }
            }
        }
            //shipping carrier config
            if(sellerWiseShippingConfig(1)['order_confirm_and_sync'] == 'Automatic'){
            $orderManageRepo = new OrderManageRepository();
            $orderManageRepo->orderConfirm($order->id);
        }

        $notificationUrl = route('frontend.my_purchase_order_detail',encrypt($order->id));
        $notificationUrl = str_replace(url('/'),'',$notificationUrl);

        $this->notificationUrl = $notificationUrl;
        $this->adminNotificationUrl = 'ordermanage/total-sales-list';
        $this->routeCheck = 'order_manage.total_sales_index';
        $this->typeId = EmailTemplateType::where('type','order_email_template')->first()->id;//order email templete type id
        $this->order_on_notification = $order;
        $notification = NotificationSetting::where('slug','new-order')->first();
        if ($notification) {
            $this->notificationSend($notification->id, $order->customer_id);
        }

        return $order;
    }

    public function orderStoreForInAppPurchse($user = null, $data)
    {
        $billing_address = null;
        $shipping_address = null;
        $delivery_type = 'email_delivery';
        $pickup_location_id = null;

        if(isset($data['delivery_type']) && $data['delivery_type'] == 'pickup_location'){
            $delivery_type = $data['delivery_type'];
            $pickup_location_id = $data['pickup_location_id'];
        }
        if ($user != null) {
            $query =  Cart::where('user_id',$user->id)->where('id',$data['cart_id'])->where('is_select',1)->where('product_type', 'product')->whereHas('product', function($query){
                return $query->where('status', 1)->whereHas('product', function($q){
                    return $q->where('status', 1)->activeSeller();
                });
            })->orWhere('product_type', 'gift_card')->where('user_id',$user->id)->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->get();

            $recs = new \Illuminate\Database\Eloquent\Collection($query);
            $productList = $recs->groupBy('seller_id');

            $customer_email = $user->email;
            $customer_phone = $user->phone;
            $is_pickup = 0;
            $shipping_address = $user->customerShippingAddress;
            $billing_address = $user->customerBillingAddress;
            if($billing_address == null){
                $billing_address = $shipping_address;
            }

            if($delivery_type == 'pickup_location'){
                $pickup_location = EntitiesPickupLocation::find($pickup_location_id);
                $is_pickup = 1;
            }
        }
        $orderDate = date("Ymd");
        $totalOrder =  Order::count() + 1;
        $nextOrder = str_pad( $totalOrder, 6, "0", STR_PAD_LEFT );
        $orderNumber = $orderDate.$nextOrder;
        $payment_method = PaymentMethod::where('slug','in-app-purchase')->first();
        $order = Order::create([
            'customer_id' => ($user != null) ? $user->id : null,
            'order_number' => $orderNumber,
            'payment_type' => $payment_method->id,
            'is_paid' => 1,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'customer_shipping_address' => @$shipping_address->id,
            'customer_billing_address' => @$billing_address->id,
            'grand_total' => $data['grand_total'],
            'sub_total' => $data['grand_total'],
            'discount_total' => $data['grand_total'],
            'shipping_total' => 0,
            'number_of_package' => 1,
            'number_of_item' => 1,
            'order_status' => 1,
            'order_payment_id' => 0,
            'tax_amount' => isset($data['tax_total']) ? $data['tax_total']:0,
            'delivery_type' => $delivery_type,
            'pickup_location_id' => $pickup_location_id,
            'is_confirmed' => 1
        ]);


        $val = 0;
        foreach ($productList as $key => $products) {
            $seller_id = $key;

            $index_no = $val + 1;
            $package = OrderPackageDetail::create([
                'order_id' => $order->id,
                'seller_id' => $seller_id,
                'package_code' => date('ymdhsi').rand(11,99),
                'number_of_product' => count($products),
                'shipping_cost' => isset($data['shipping_cost'][$val]) ? $data['shipping_cost'][$val]:0,
                'shipping_date' => isset($data['delivery_date'][$val]) ? $data['delivery_date'][$val]:date("Y-m-d"),
                'shipping_method' => 1,
                'carrier_id' => 1,
                'pickup_point_id' => null,
                'tax_amount' => isset($data['packagewiseTax'][$val]) ? $data['packagewiseTax'][$val]:0,
                'weight' => isset($data['package_wise_weight'][$val]) ? $data['package_wise_weight'][$val]:0,
                'height' => isset($data['package_wise_height'][$val]) ? $data['package_wise_height'][$val]:0,
                'length' => isset($data['package_wise_length'][$val]) ? $data['package_wise_length'][$val]:0,
                'breadth' => isset($data['package_wise_breadth'][$val]) ? $data['package_wise_breadth'][$val]:0,
                'delivery_status' => 1
            ]);
            $val++;

            if ($user != null) {

                $is_same_billing = 1;
                if($order->customer_shipping_address != $order->customer_billing_address){
                    $is_same_billing = 0;
                }

                $address = OrderAddressDetail::create([
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'shipping_name' => $user->first_name.' '. $user->last_name,
                    'shipping_email' => $user->email,
                    'shipping_phone' => $user->phone,
                    'shipping_address' => $is_pickup?$pickup_location->address:$shipping_address->address,
                    'shipping_country_id' => $is_pickup?$pickup_location->country_id:$shipping_address->country,
                    'shipping_state_id' => $is_pickup?$pickup_location->state_id:$shipping_address->state,
                    'shipping_city_id' => $is_pickup?$pickup_location->city_id:$shipping_address->city,
                    'shipping_postcode' => $is_pickup?$pickup_location->pin_code:$shipping_address->postal_code,
                    'bill_to_same_address' => $is_same_billing,
                    'billing_name' => $billing_address->name,
                    'billing_email' => $billing_address->email,
                    'billing_phone' => $billing_address->phone,
                    'billing_address' => $billing_address->address,
                    'billing_country_id' => $billing_address->country,
                    'billing_state_id' => $billing_address->state,
                    'billing_city_id' => $billing_address->city,
                    'billing_postcode' => $billing_address->postal_code,
                ]);

                foreach ($products as $key => $product) {
                    if(isset($data['product_info']) && count($data['product_info']) > 0){
                        $payload_product_info = isset($data['product_info'][$product->product_id]) ? (object)$data['product_info'][$product->product_id]:null;
                    }else{
                        $payload_product_info = null;
                    }
                    if($payload_product_info){
                        $total_price = $payload_product_info->total_price;
                        $price = $payload_product_info->price;
                    }else{
                        $total_price = $product->total_price;
                        $price = $product->price;
                    }
                    if ($product->product_type == "gift_card") {
                        $seller_product = GiftCard::find($product->product_id);
                         // affiliate
                        if (isModuleActive('Affiliate') ) {
                            Event::dispatch(new ReferralPayment(0,$product->giftCard->id,$total_price));
                        }
                        $e_items[]=[
                            "item_id"=> $product->giftCard->id,
                            "item_name"=> $product->giftCard->name,
                            "currency"=> $user->currency_code,
                            "price"=> $price
                        ];
                        $tax = 0;
                    } else {
                        $seller_product = SellerProductSKU::find($product->product_id)->product;
                        $seller_product->update([
                            'total_sale' => $seller_product->total_sale + $product->qty
                        ]);
                        if ($seller_product->product->category_id != null || $seller_product->product->category_id != 0) {
                            $category = $seller_product->product->category;
                            $seller_product->product->category->update(['total_sale' => $category->total_sale + $product->qty]);
                        }
                        if ($seller_product->product->brand_id != null || $seller_product->product->brand_id != 0) {
                            $brand = $seller_product->product->brand;
                            $seller_product->product->brand->update(['total_sale' => $brand->total_sale + $product->qty]);
                        }

                        // affiliate
                        if (isModuleActive('Affiliate') ) {
                            if($user->isReferralUser){
                                Event::dispatch(new ReferralPayment($user->id,$product->product->product->id,$total_price));
                            }else{
                                Event::dispatch(new ReferralPayment(0,$product->product->product->id,$total_price));
                            }
                        }
                        $e_items[]=[
                            "item_id"=> !empty($product->product) && !empty($product->product->sku) ? $product->product->sku->sku:'',
                            "item_name"=> $product->product->product->product_name,
                            "currency"=> $user->currency_code,
                            "price"=> $price
                        ];

                        $seller = User::find($seller_id);
                        $address_state = $address->shipping_state_id;
                        $tax = $this->getProductTax($product, $seller, $address_state);
                    }

                    OrderProductDetail::create([
                        'package_id' => $package->id,
                        'type' => $product['product_type'],
                        'product_sku_id' => $product->product_id,
                        'qty' => $product->qty,
                        'price' => $price,
                        'total_price' => $total_price,
                        'tax_amount' => $tax,
                        'gift_card_sku' => !empty($product->gift_card_sku) ? $product->gift_card_sku:null,
                        'gift_card_type' => !empty($product->gift_card_type) ? $product->gift_card_type:''
                    ]);

                }
            } else {

                foreach ($products as $key => $product) {
                    if(isset($data['product_info']) && count($data['product_info']) > 0){
                        $payload_product_info = (object) $data['product_info'][$product->product_id];
                    }else{
                        $payload_product_info = null;
                    }
                    if($payload_product_info){
                        $total_price = $payload_product_info->total_price;
                        $price = $payload_product_info->price;
                    }else{
                        $total_price = $product->total_price;
                        $price = $product->price;
                    }

                    if ($product['product_type'] == "gift_card") {
                        $seller_product = GiftCard::find($product->product_id);

                        //affiliate
                        if (isModuleActive('Affiliate') ) {
                            Event::dispatch(new ReferralPayment(0,$seller_product->id,$total_price));
                        }
                    } else {
                        $seller_product = SellerProductSKU::find($product->product_id)->product;
                        $seller_product->update([
                            'total_sale' => $seller_product->total_sale + $product['qty']
                        ]);
                        if ($seller_product->product->category_id != null || $seller_product->product->category_id != 0) {
                            $category = $seller_product->product->category;
                            $seller_product->product->category->update(['total_sale' => $category->total_sale + $product->qty]);
                        }
                        if ($seller_product->product->brand_id != null || $seller_product->product->brand_id != 0) {
                            $brand = $seller_product->product->brand;
                            $seller_product->product->brand->update(['total_sale' => $brand->total_sale + $product->qty]);
                        }

                        // affiliate
                        if (isModuleActive('Affiliate') ) {
                            Event::dispatch(new ReferralPayment(0,$product->product->product->id,$total_price));
                        }
                    }

                    OrderProductDetail::create([
                        'package_id' => $package->id,
                        'type' => $product['product_type'],
                        'product_sku_id' => $product['product_id'],
                        'qty' => $product->qty,
                        'price' => $price,
                        'total_price' =>  $total_price,
                        'tax_amount' => ($product->product_type == "product") ? (tax_count($price, $product->product->product->tax, $product->product->product->tax_type) * $product->qty) : 0
                    ]);

                }

            }



        }
        if ($user != null) {
            $carts = Cart::where('user_id', $user->id)->where('is_select', 1)->get();
            foreach ($carts as $cart) {
                $cart->delete();
            }
        }



        $seller_to_seller_payment = 0;
        if(isModuleActive('MultiVendor') && session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout')){
            $seller_to_seller_payment = 1;
        }
        $order_payment = OrderPayment::create([
            'user_id' => ($user != null) ? $user->id : null,
            'amount' => $data['grand_total'],
            'payment_method' => !empty($payment_method) ? $payment_method->id:'',
            'txn_id' => "inapp-".date('ymd').$order->id,
            'amount_goes_to_seller' => $seller_to_seller_payment
        ]);

        $order->update([
            'order_payment_id' => $order_payment->id
        ]);


        if ($user != null) {
            $referral_code = ReferralCode::where('user_id', $user->id)->first();
            if (!isset($referral_code)) {
                ReferralCode::create([
                    'user_id' => $user->id,
                    'referral_code' => $user->id . rand(111111111, 999999999),
                    'status' => 1
                ]);
            }
            $referral_use = ReferralUse::where('user_id', $user->id)->where('is_use', 0)->first();
            if (isset($referral_use)) {

            }

            if (isset($data['coupon_id'])) {
                $coupon = Coupon::find($data['coupon_id']);
                $couponUse = CouponUse::where('user_id', $user->id)->where('coupon_id', $data['coupon_id'])->first();
                if (isset($couponUse) && $coupon) {
                    if ($coupon->is_multiple_buy == 1) {
                        CouponUse::create([
                            'user_id' => $user->id,
                            'coupon_id' => $data['coupon_id'],
                            'order_id' => $order->id,
                            'discount_amount' => $data['coupon_amount'],
                        ]);
                        session()->forget('coupon_type');
                        session()->forget('coupon_discount');
                        session()->forget('coupon_discount_type');
                        session()->forget('maximum_discount');
                        session()->forget('maximum_products');
                        session()->forget('coupon_id');
                    }
                } else {
                    CouponUse::create([
                        'user_id' => $user->id,
                        'coupon_id' => $data['coupon_id'],
                        'order_id' => $order->id,
                        'discount_amount' => $data['coupon_amount'],
                    ]);
                    session()->forget('coupon_type');
                    session()->forget('coupon_discount');
                    session()->forget('coupon_discount_type');
                    session()->forget('maximum_discount');
                    session()->forget('maximum_products');
                    session()->forget('coupon_id');
                }
            }
        }


        $notificationUrl = route('frontend.my_purchase_order_detail',encrypt($order->id));
        $notificationUrl = str_replace(url('/'),'',$notificationUrl);

        $this->notificationUrl = $notificationUrl;
        $this->adminNotificationUrl = 'ordermanage/total-sales-list';
        $this->routeCheck = 'order_manage.total_sales_index';
        $this->typeId = EmailTemplateType::where('type','order_email_template')->first()->id;//order email templete type id
        $this->order_on_notification = $order;
        $notification = NotificationSetting::where('slug','new-order')->first();
        if ($notification) {
            $this->notificationSend($notification->id, $order->customer_id);
        }

        return $order;
    }

    public function orderPaymentDone($amount, $method, $response, $user = null)
    {
        $seller_to_seller_payment = 0;
        if(isModuleActive('MultiVendor') && session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout')){
            $seller_to_seller_payment = 1;
        }
        if($method != 1 && $method != 2 && $method != 7){
            $old_tnx = OrderPayment::where('txn_id', $response)->first();
            if($old_tnx){
                return 'failed';
            }else{
                $order_payment = OrderPayment::create([
                    'user_id' => ($user != null) ? $user->id : null,
                    'amount' => $amount,
                    'payment_method' => $method,
                    'txn_id' => $response,
                    'status' => 1,
                    'amount_goes_to_seller' => $seller_to_seller_payment
                ]);
                return $order_payment;
            }
        }elseif($method == 7){
            $order_payment = OrderPayment::create([
                'user_id' => ($user != null) ? $user->id : null,
                'amount' => $amount,
                'payment_method' => $method,
                'txn_id' => $response,
                'amount_goes_to_seller' => $seller_to_seller_payment
            ]);
            return $order_payment;
        }
        $order_payment = OrderPayment::create([
            'user_id' => ($user != null) ? $user->id : null,
            'amount' => $amount,
            'payment_method' => $method,
            'txn_id' => $response,
        ]);
        return $order_payment;
    }

    public function orderPaymentDelete($id)
    {
        return OrderPayment::findOrFail($id)->delete();
    }

    public function getOrderToShip($user_id)
    {

        return OrderPackageDetail::with('order.address.getShippingCountry','order.address.getShippingState','order.address.getShippingCity','order.address.getBillingCountry','order.address.getBillingState','order.address.getBillingCity',
         'seller', 'delivery_states', 'products.seller_product_sku.product.product', 'products.seller_product_sku.product_variations.attribute', 'products.seller_product_sku.product_variations.attribute_value.color', 'products.seller_product_sku.sku','products.giftCard')->where('delivery_status', '>', 1)->where('delivery_status', '<=', 2)->whereHas('order', function ($query) use ($user_id) {
            $query->where('customer_id', $user_id)->where('is_confirmed', 1);
        })->get();
    }

    public function orderByDeliveryStatus($data, $user_id)
    {
        return OrderPackageDetail::with('order.address.getShippingCountry','order.address.getShippingState','order.address.getShippingCity','order.address.getBillingCountry','order.address.getBillingState','order.address.getBillingCity',
        'seller', 'delivery_states', 'products.seller_product_sku.product.product', 'products.seller_product_sku.product_variations.attribute', 'products.seller_product_sku.product_variations.attribute_value.color', 'products.seller_product_sku.sku','products.giftCard')->where('delivery_status', $data['status'])->whereHas('order', function ($query) use ($user_id) {
           $query->where('customer_id', $user_id)->where('is_confirmed', 1);
       })->get();
    }



    public function getOrderToReceive($user_id)
    {
        return OrderPackageDetail::with('order.address.getShippingCountry','order.address.getShippingState','order.address.getShippingCity','order.address.getBillingCountry','order.address.getBillingState','order.address.getBillingCity',
         'seller', 'delivery_states', 'products.seller_product_sku.product.product', 'products.seller_product_sku.product_variations.attribute', 'products.seller_product_sku.product_variations.attribute_value.color', 'products.seller_product_sku.sku','products.giftCard')->where('delivery_status', '>', 2)->where('delivery_status', '<=', 3)->whereHas('order', function ($query) use ($user_id) {
            $query->where('customer_id', $user_id)->where('is_confirmed', 1);
        })->get();
    }

    public function getNumberOfOrdersCancelled($user){
        $orders = Order::where('customer_id', $user->id)->where('is_cancelled', 1)->pluck('id');
        return count($orders);
    }

    public function purchaseHistories($filter = null, $club_point = false){
        session()->forget('purchase_history_filter');
        if($filter){
            if($filter == 'pending'){
                $orders = OrderPackageDetail::with(['order','products'])->whereHas('order', function($query) use($club_point){
                    $query->where('customer_id', auth()->id())->where('is_confirmed', 0)->where('is_cancelled', 0)->when($club_point,function($q){
                        $q->where('club_point','>=',1);
                    });
                })->where('delivery_status', 1)->latest()->paginate(10);
                session()->put('purchase_history_filter','pending');
            }
            elseif($filter == 'confirm'){
                $orders = OrderPackageDetail::with(['order','products'])->whereHas('order', function($query)use ($club_point){
                    $query->where('customer_id', auth()->id())->where('is_confirmed', 1)->when($club_point,function($q){
                        $q->where('club_point','>=',1);
                    });
                })->where('is_cancelled', 0)->latest()->paginate(10);
                session()->put('purchase_history_filter','confirm');
            }
            elseif($filter == 'complete'){
                $orders = OrderPackageDetail::with(['order','products'])->whereHas('order', function($query)use ($club_point){
                    $query->where('customer_id', auth()->id())->where('is_confirmed', 1)->where('is_completed', 1)->when($club_point,function($q){
                        $q->where('club_point','>=',1);
                    });
                })->where('delivery_status','>=',5)->latest()->paginate(10);
                session()->put('purchase_history_filter','complete');
            }
            elseif($filter == 'cancel'){
                $orders = OrderPackageDetail::with(['order','products'])->whereHas('order', function($query)use ($club_point){
                    $query->where('customer_id', auth()->id())->when($club_point,function($q){
                        $q->where('club_point','>=',1);
                    });
                })->where('is_cancelled', 1)->latest()->paginate(10);
                session()->put('purchase_history_filter','cancel');
            }else{
                $orders = OrderPackageDetail::with(['order','products'])->whereHas('order', function($query)use ($club_point){
                    $query->where('customer_id', auth()->id())->when($club_point,function($q){
                        $q->where('club_point','>=',1);
                    });
                })->latest()->paginate(10);
                session()->put('purchase_history_filter','all');
            }
        }else{

            $orders = OrderPackageDetail::with(['order','products'])->whereHas('order', function($query)use ($club_point){
                    $query->where('customer_id', auth()->id())->when($club_point,function($q){
                        $q->where('club_point','>=',1);
                    });
                })->latest()->paginate(10);
            session()->put('purchase_history_filter','all');
        }

        return $orders;
    }

    public function getOrderPackage($data){
        return OrderPackageDetail::with(['order.address','order.order_payment','products.seller_product_sku.product','products.giftCard','carrier'])->where('id', $data['order_id'])->first();
    }
}
