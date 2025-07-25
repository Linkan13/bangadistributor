<?php

namespace App\Http\Controllers\Frontend;
use Exception;
use App\Traits\Otp;
use App\Traits\OrderPdf;
use App\Traits\SendMail;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Modules\Setup\Entities\City;
use Modules\Setup\Entities\State;
use App\Models\OrderProductDetail;
use Illuminate\Support\Facades\DB;
use App\Models\DigitalFileDownload;
use Modules\Setup\Entities\Country;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Modules\Seller\Entities\SellerProduct;
use Modules\Seller\Entities\SellerProductSKU;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\Wallet\Repositories\WalletRepository;
use Modules\Bkash\Http\Controllers\BkashController;
use Modules\Setup\Entities\OneClickorderReceiveStatus;
use Modules\CCAvenue\Http\Controllers\CCAvenueController;
use Modules\Clickpay\Http\Controllers\ClickpayController;
use Modules\OrderManage\Repositories\CancelReasonRepository;
use Modules\PaymentGateway\Http\Controllers\PaytmController;
use Modules\PaymentGateway\Http\Controllers\PayPalController;
use Modules\PaymentGateway\Http\Controllers\StripeController;
use Modules\MercadoPago\Http\Controllers\MercadoPagoController;
use Modules\OrderManage\Repositories\DeliveryProcessRepository;
use Modules\PaymentGateway\Http\Controllers\MidtransController;
use Modules\PaymentGateway\Http\Controllers\PaystackController;
use Modules\PaymentGateway\Http\Controllers\RazorpayController;
use Modules\PaymentGateway\Http\Controllers\InstamojoController;
use Modules\PaymentGateway\Http\Controllers\PayUmoneyController;
use Modules\SslCommerz\Library\SslCommerz\SslCommerzNotification;
use Modules\PaymentGateway\Http\Controllers\BankPaymentController;

use Modules\PaymentGateway\Http\Controllers\FlutterwaveController;

use Modules\PaymentGateway\Http\Controllers\TabbyPaymentController;
use Modules\Shipping\Http\Controllers\OrderSyncWithCarrierController;

class OrderController extends Controller
{

    use OrderPdf, SendMail, Otp, SerializesModels;
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->middleware('maintenance_mode');
    }

    public function my_purchase_order_index(Request $request)
    {
        if ($request->has('rn')) {
            $data['orders'] = $this->orderService->myPurchaseOrderListwithRN($request->rn);
            $data['rn'] = $request->rn;
        } else {
            $data['orders'] = $this->orderService->myPurchaseOrderList();
        }
        $cancelReasonRepo = new CancelReasonRepository;
        $data['cancel_reasons'] = $cancelReasonRepo->getAll();
        $data['no_paid_orders'] = $this->orderService->myPurchaseOrderListNotPaid();
        $data['to_shippeds'] = $this->orderService->myPurchaseOrderPackageListShipped();
        $data['to_recieves'] = $this->orderService->myPurchaseOrderPackageListRecieved();

        if (auth()->user()->role->type != 'customer') {
            return view('backEnd.pages.customer_data.order', $data);
        } else {
            return view(theme('pages.profile.order'), $data);
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'payment_method' => 'required',
            'grand_total' => 'required',
            'sub_total' => 'required',
            'number_of_package' => 'required',
            'number_of_item' => 'required',
            'shipping_cost' => 'required',
            'shipping_method' => 'required',
            'delivery_date' => 'required',
            'carts' => 'required'
        ]);

        if (isModuleActive('Otp') && otp_configuration('otp_activation_for_order') && $request->payment_method == 1) {
            if(auth()->check()){
                $cancel_orders = $this->orderService->getNumberOfOrdersCancelled(auth()->user());
                if(auth()->user()->is_verified == 1 && otp_configuration('order_otp_on_verified_customer') && otp_configuration('order_cancel_limit_on_verified_customer') < $cancel_orders){
                    return $this->sendOtpOnOrder($request);
                }elseif(auth()->user()->is_verified == 0){
                    return $this->sendOtpOnOrder($request);
                }
            }
        }

        try {
            DB::beginTransaction();
            $order = $this->orderService->orderStore($request->except('_token'));
            DB::commit();
            if (app('business_settings')->where('type', 'mail_notification')->first()->status == 1) {
                $this->sendInvoiceMail($order->order_number, $order);
            }
            Toastr::success(__('order.oredre_created_successfully'), __('common.success'));
            LogActivity::successLog('order store successful.');
            return redirect()->route('frontend.order.summary_after_checkout', encrypt($order->id));
        } catch (Exception $e) {
            DB::rollback();
            LogActivity::errorLog($e->getMessage());
            DB::rollback();
            Toastr::error(__('order.order_submit_failed'));
            return back();
        }
    }

    private function sendOtpOnOrder($request){
        try {
            if (!$this->sendOtpForOrder($request)) {
                Toastr::error(__('otp.something_wrong_on_otp_send'), __('common.error'));
                return back();
            }
            session()->put('request', $request->all());
            return view(theme('pages.order_otp'), compact('request'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('otp.something_wrong_on_otp_send'), __('common.error'));
            return back();
        }
    }

    public function payment(Request $request)
    {
        $method = json_decode($request->method);
        if (json_last_error() === JSON_ERROR_NONE) {
            $payment_method  = $method->en;
        }else{
            $payment_method = $request->method;
        }



        session()->put('order_payment', '1');
        if ($payment_method == "Stripe") {
            $data['gateway_id'] = encrypt(4);
            $stripeController = new StripeController;
            $response = $stripeController->stripePost($request->all());
            if(gettype($response) == 'object'){
                return redirect()->back();
            }
        }elseif ($payment_method == "RazorPay") {
            $data['gateway_id'] = encrypt(6);
            $razorpayController = new RazorpayController;
            $response = $razorpayController->paynment($request->all());
        }elseif ($payment_method == "Paypal") {
            $data['gateway_id'] = encrypt(3);
            $paypalController = new PayPalController;
            $response = $paypalController->payment($request->all());
        }elseif ($payment_method == "Paystack") {
            $data['gateway_id'] = encrypt(5);
            DB::table('payment_meta_datas')->insert([
                "method_id" => 5,
                "user_id" => auth()->id(),
                "amount" => $request->amount,
                "payment_for" => 'order_payment'
            ]);
            $paystackController = new PaystackController;
            return $paystackController->redirectToGateway();
        }elseif ($payment_method == "BankPayment") {
            $data['gateway_id'] = encrypt(7);
            $bankController = new BankPaymentController;
            $response = $bankController->store($request->all());
        }elseif ($payment_method == "PayTm") {
            $paytm = new PaytmController;
            return $paytm->payment($request->all());
        }elseif ($payment_method == "Instamojo") {
            $instamojo = new InstamojoController;
            return $instamojo->paymentProcess($request->all());
        }elseif ($payment_method == "Midtrans") {
            $midtrans = new MidtransController;
            return $midtrans->paymentProcess($request->all());
        }elseif ($payment_method == "PayUMoney") {
            $PayUMoney = new PayUmoneyController;
            return $PayUMoney->payment($request->all());
        }elseif ($payment_method == "flutterwave") {
            $flutterWaveController = new FlutterwaveController;
            return $flutterWaveController->payment($request->all());
        }elseif ($payment_method == "Bkash") {
            $data['gateway_id'] = encrypt(15);
            $bkashController = new BkashController();
            $response = $bkashController->bkashSuccess($request->all());
        }elseif($payment_method == "SslCommerz" ){
            $post_data = array();
            $post_data['total_amount'] = $request->amount; # You cant not pay less than 10
            $post_data['currency'] = "BDT";
            $post_data['tran_id'] = uniqid(); // tran_id must be unique

            # CUSTOMER INFORMATION
            $post_data['cus_name'] = 'Customer Name';
            $post_data['cus_email'] = 'customer@mail.com';
            $post_data['cus_add1'] = 'Customer Address';
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = "";
            $post_data['cus_state'] = "";
            $post_data['cus_postcode'] = "";
            $post_data['cus_country'] = "Bangladesh";
            $post_data['cus_phone'] = '8801XXXXXXXXX';
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = "Store Test";
            $post_data['ship_add1'] = "Dhaka";
            $post_data['ship_add2'] = "Dhaka";
            $post_data['ship_city'] = "Dhaka";
            $post_data['ship_state'] = "Dhaka";
            $post_data['ship_postcode'] = "1000";
            $post_data['ship_phone'] = "";
            $post_data['ship_country'] = "Bangladesh";

            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Computer";
            $post_data['product_category'] = "Goods";
            $post_data['product_profile'] = "physical-goods";

            # OPTIONAL PARAMETERS
            $post_data['value_a'] = "ref001";
            $post_data['value_b'] = "ref002";
            $post_data['value_c'] = "ref003";
            $post_data['value_d'] = "ref004";

            session(['ssl_payment_type' => $request->type]);
            $url = explode('?',url()->previous());
            if(isset($url[0]) && $url[0] == url('/checkout')){
                $is_checkout = true;
            }else{
                $is_checkout = false;
            }

            if(session()->has('order_payment') && app('general_setting')->seller_wise_payment && session()->has('seller_for_checkout') && $is_checkout){
                $credential = getPaymentInfoViaSellerId(session()->get('seller_for_checkout'), 16);
            }else{
                $credential = getPaymentInfoViaSellerId(1, 16);
            }
            $sslc = new SslCommerzNotification($credential);
            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($post_data);
            $payment_options = \GuzzleHttp\json_decode($payment_options);
            if ($payment_options->status == "success") {
                 return Redirect::to($payment_options->data);
            } else {
                Toastr::error('Something went wrong', 'Failed');
                return redirect(url('/checkout'));
            }
        }elseif ($payment_method == "MercadoPago") {
            $mercadoPagoController = new MercadoPagoController();
            $response = $mercadoPagoController->payment($request->all());
            $c_data['payment_id'] = encrypt($response);
            $c_data['step'] = 'complete_order';
            $c_data['gateway_id'] = encrypt(17);
            return response()->json(['target_url'=>route('frontend.checkout', $c_data)]);
        }
        elseif ($payment_method == "Tabby") {

            if(!auth()->check() ){
                Toastr::error(__('Pleaes login to pay with Tabby'),__('common.error'));
                return back();
            }

            if(getCurrencyCode() != 'AED'){
                Toastr::error(__('payment_gatways.Tabby Gateway Only Support AED Currency'),__('common.error'));
                return back();
            }

            $checkoutRepo = new \App\Repositories\CheckoutRepository();
            $cartData = $checkoutRepo->getCartItem();

            $carts = $cartData['cartData'];
            $items = [];
            foreach($carts[1] as $key => $cart){

                $title = !empty($cart->product) && !empty($cart->product->product) ? $cart->product->product->product_name:'Gold Item';
                $items[] = [
                        "title" => (string) $title,
                        "quantity" => (int) $cart->qty,
                        "unit_price" => str_replace(',','',number_format($cart->price,2)),
                        "category" => "Products"
                ];
            }

            $address = \Modules\Customer\Entities\CustomerAddress::where('customer_id',auth()->user()->id)->with(['getCity'])->first();

            $shipping_address = [
                "city" => !empty($address->getCity) ? $address->getCity->name:'Dubai',
                "address" => !empty($address) ?  $address->address:"Dubai" ,
                "zip" => !empty($address) ? $address->postal_code:'1250',
                "phone" => $address->phone
            ];

            $data['gateway_id'] = encrypt(18);

            $tabbyPaymentController = new TabbyPaymentController();
            $response = $tabbyPaymentController->paymentProcess($request->all(),$items,$shipping_address);
            if($response == "no-phone"){
                return redirect()->back();
            }
            if(!$response){
              return redirect()->back();
            }

        }
        elseif ($payment_method == "CCAvenue") {
            $data['gateway_id'] = encrypt(19);
            $ccavenueController = new CCAvenueController();
            $response = $ccavenueController->paymentProcess($request->all());
        }elseif($payment_method == 'Clickpay'){

            $data['name'] = $request->customer_name;
            $data['amount'] = $request->amount;
            $data['email'] = $request->customer_email;
            $data['phone'] = $request->customer_phone;
            $data['zip'] = $request->customer_postal_code;
            $data['description'] = "Products Checkout";
            $data['callback'] = route('clickpay.callback');
            $data['return'] = route('clickpay.return');
            $data['address'] = $request->customer_address;
            $state = State::find($request->customer_state);
            $data['state'] = !empty($state) ?$state->name:'Riyad';
            $city = City::find($request->customer_city);
            $data['city'] = !empty($city) ? $city->name:'Ar-Riyad';
            $country = Country::find($request->customer_country);
            $data['country'] = !empty($country) ? $country->code:'SA';
            $clickpay = new ClickpayController();
            $data = $clickpay->payment($data);
            if($data != false){
                return redirect()->to($data)->send();
            }else{
                Toastr::error(trans('common.Something Went Wrong'),trans('common.error'));
                return back();
            }
        }else{
            return redirect()->back();
        }
        $data['payment_id'] = encrypt($response);
        $data['step'] = 'complete_order';
        return redirect()->route('frontend.checkout', $data);
    }

    public function my_purchase_order_detail($id)
    {
        try {
            $data['order_status'] = [];
            $data['order'] = $this->orderService->orderFindByID(decrypt($id));
            $orderSyncWithCarrierController = new OrderSyncWithCarrierController();

            foreach ($data['order']->packages as $package){
                $status = $orderSyncWithCarrierController->orderTracking($package->carrier_order_id);
                $data['order_status'][$package->id] = $status;
            }

            $data['oneClickOrderComplete'] = OneClickorderReceiveStatus::first();
            $orderDeliveryRepo = new DeliveryProcessRepository;
            $data['processes'] = $orderDeliveryRepo->getAll();
            $cancelReasonRepo = new CancelReasonRepository;
            $data['cancel_reasons'] = $cancelReasonRepo->getAll();
            if (auth()->check() && auth()->user()->role->type != 'customer') {
                return view('backEnd.pages.customer_data.order_details',$data);
            }else {
                if (auth()->check() && $data['order']->customer_id != null) {
                    return view(theme('pages.profile.order_details'), $data);
                } else {
                    return view(theme('pages.profile.order_details_for_guest'), $data);
                }
            }
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return back();
        }
    }

    public function changeReceiveStatusByCustomer(Request $request)
    {
        try {
            $order = $this->orderService->changeReceiveStatusByCustomer($request->except('_token'));
            LogActivity::successLog('Product Successfully Received by customer.');
            return 1;
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return 0;
        }
    }

    public function my_purchase_order_pdf($id)
    {
        try {
            $order = $this->orderService->orderFindByID(decrypt($id));
            return $this->order_pdf(theme('pages.profile.order_pdf'), $order);
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function my_purchase_order_cancel(Request $request)
    {

        $request->validate([
            'order_id' => 'required',
            'reason' => 'required',
        ]);
        try {
            $data = $this->orderService->orderFindByID($request->order_id);

            if (auth()->id() == $data->customer_id && $data->is_confirmed != 1) {
                $data->is_cancelled = 1;
                $data->cancel_reason_id = $request->reason;
                $data->save();
                foreach($data->packages as $pkg){
                    $pkg->update([
                        'is_cancelled' => 1
                    ]);
                }
                if(isModuleActive('Affiliate') && $data->affiliatePayments->count() > 0){
                    foreach($data->affiliatePayments as $key => $aff_payment){
                        $aff_payment->update([
                            'status' => 2
                        ]);
                    }
                }
                if(@$data->order_payment->payment_method == 2 && $data->customer_id){
                    $wallet_service = new WalletRepository;
                    $wallet_service->cartPaymentData($data->id, $data->grand_total, "Refund Back", $data->customer_id, 'registered');
                }
                LogActivity::successLog('Purchase order cancel successful for ' . auth()->user()->first_name);
                Toastr::success(__('order.your_order_has_been_cancelled'));
            } else {
                Toastr::error(__('order.order_cancellation_failed'));
            }

            return back();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('order.your_order_cancellation_has_been_failed_try_again'));
            return back();
        }
    }

    public function my_purchase_order_package_cancel(Request $request)
    {

        try {
            $data = $this->orderService->orderPackageFindByID($request->order_id);
            $orderProductDetail = OrderProductDetail::where('package_id',$data->id)->get();

            if ($data->order->is_cancelled == 0) {
                $data->is_cancelled = 1;
                $data->cancel_reason_id = $request->reason;
                $data->save();


                foreach($orderProductDetail as $detail){
                    $sellerProductSku = SellerProductSKU::findOrFail($detail->product_sku_id);
                    $sellerProductSku->product_stock = $sellerProductSku->product_stock + $detail->qty;
                    if ($sellerProductSku->product) {
                        $sellerProduct = SellerProduct::findOrFail($sellerProductSku->product->id);
                        $sellerProduct->total_sale = $sellerProductSku->product->total_sale - $detail->qty;
                        $sellerProduct->save();
                    }
                    $sellerProductSku->save();
                }

                if(!isModuleActive('MultiVendor') || @$data->order->packages->count() < 2){
                    $data->order->update([
                        'is_cancelled' => 1
                    ]);

                    if(@$data->order->order_payment->payment_method == 2 && $data->order->customer_id){
                        $wallet_service = new WalletRepository;
                        $wallet_service->cartPaymentData($data->order->id, $data->order->grand_total, "Refund Back", $data->order->customer_id, 'registered');
                    }
                }else{
                    if(@$data->order->order_payment->payment_method == 2 && $data->order->customer_id){
                        $wallet_service = new WalletRepository;
                        $package_order_amount = $data->products->sum('total_price') + $data->shipping_cost + $data->tax_amount;
                        $wallet_service->cartPaymentData($data->order->id, $package_order_amount, "Refund Back", $data->order->customer_id, 'registered');
                    }
                }




                Toastr::success(__('order.your_package_order_has_been_cancelled'));
                LogActivity::successLog('My purchase order package cancel successful.');
            } else {
                Toastr::error(__('order.package_order_cancellation_failed'));
            }
            return back();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('order.your_order_cancellation_has_been_failed_try_again'));
            return back();
        }
    }
    //guest order cancell
    public function my_purchase_order_package_cancel_guest(Request $request){
        try {
            $data = $this->orderService->orderPackageFindByID($request->order_id);
            if ($data->order->is_cancelled == 0) {
                $data->is_cancelled = 1;
                $data->cancel_reason_id = $request->reason;
                $data->save();

                if(!isModuleActive('MultiVendor') || @$data->order->packages->count() < 2){
                    $data->order->update([
                        'is_cancelled' => 1
                    ]);
                }

                Toastr::success(__('order.your_package_order_has_been_cancelled'));
                LogActivity::successLog('My purchase order package cancel successful.');
            } else {
                Toastr::error(__('order.package_order_cancellation_failed'));
            }
            return back();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('order.your_order_cancellation_has_been_failed_try_again'));
            return back();
        }
    }

    public function order_summary($id)
    {
        try {
            $data['order'] = $this->orderService->orderFindByID(decrypt($id));
            return view(theme('pages.checkout_summary'), $data);
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json($e);
        }
    }

    public function track_order()
    {
        return view(theme('pages.track_order'));
    }

    public function track_order_find(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:App\Models\Order,order_number',
            'secret_id' => 'nullable|exists:App\Models\GuestOrderDetail,guest_id'
        ]);
        try {
            if (auth()->check()) {
                $data['order'] = $this->orderService->orderFindByOrderNumber($request->except('_token'), auth()->user());
            } else {
                $data['order'] = $this->orderService->orderFindByOrderNumber($request->except('_token'), null);
            }
            if ($data['order'] == "Invalid Tracking ID") {
                Toastr::error($data['order']);
                return back();
            } elseif ($data['order'] == "Invalid Secret ID") {
                Toastr::error($data['order']);
                return back();
            } else {
                return redirect()->route('frontend.my_purchase_order_detail', encrypt($data['order']->id));
            }
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return back();
        }
    }

    public function digital_product_index()
    {
        $data['digital_products'] = DigitalFileDownload::where('customer_id', auth()->user()->id)->latest()->paginate(10);
        if (auth()->user()->role->type != 'customer') {
            return view('backEnd.pages.customer_data.digital_purchased', $data);
        } else {
            return view(theme('pages.profile.digital_purchased'), $data);
        }
    }

    public function myPurchaseHistories(Request $request){
        $filter = $request->get('filter');
        $orders = $this->orderService->purchaseHistories($filter);
        return view(theme('pages.profile.purchase_histories'),compact('orders'));
    }

    public function myPurchaseHistoryModal(Request $request){

        $package = $this->orderService->getOrderPackage($request->except('_token'));
        return (string)view(theme('pages.profile.partials.purchase_order_modal'),compact('package'));
    }
}
