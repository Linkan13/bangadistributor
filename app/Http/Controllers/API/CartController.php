<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartsResource;
use App\Repositories\CheckoutRepository;
/**
* @group Cart
*
* APIs for customer cart
*/
class CartController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /* *
     * Cart List
     * @response{
     *      "carts": {
     *           "4": [
     *               {
     *                   "id": 1,
     *                   "user_id": 5,
     *                   "seller_id": 4,
     *                   "product_type": "product",
     *                   "product_id": 7,
     *                   "qty": 1,
     *                   "price": 6550,
     *                   "total_price": 6550,
     *                   "sku": null,
     *                   "is_select": 0,
     *                   "shipping_method_id": 2,
     *                   "created_at": "2021-06-10T12:29:09.000000Z",
     *                   "updated_at": "2021-06-10T12:29:09.000000Z",
     *                   "product": {
     *
     *                   },
     *                   "shipping_method": {
     *                       "id": 1,
     *                       "method_name": "Email Delivery (within 24 Hours)",
     *                       "logo": null,
     *                       "phone": null,
     *                       "shipment_time": "12-24 hrs",
     *                       "cost": 0,
     *                       "is_active": 1,
     *                       "created_at": null,
     *                       "updated_at": null
     *                   },
     *                   "seller": {
     *                       seller info
     *
     *                   },
     *                   "customer": {
     *                   customer info...
     *                   },
     *                   "gift_card": {
     *                       giftcard info....
     *                   },
     *                   "product": {
     *                       product info...
     *                   }
     *               }
     *           ]
     *       },
     *      "shipping_charge" : "90"
     *      ,
     *       "message": "success"
     * }
     */



    public function list(Request $request){

        if(!empty($request->user_id))
        {
            $cart_ids = Cart::where('user_id',$request->user_id)->where('product_type', 'product')->whereHas('product', function($query){
             return $query->where('status', 1)->whereHas('product', function($q){
                return $q->where('status', 1)->activeSeller();
            });
            })->orWhere('user_id',$request->user_id)->where('product_type', 'gift_card')->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->pluck('id')->toArray();
        }else{
            $cart_ids = Cart::where('session_id',$request->device_token)->where('product_type', 'product')->whereHas('product', function($query){
             return $query->where('status', 1)->whereHas('product', function($q){
                return $q->where('status', 1)->activeSeller();
            });
            })->orWhere('session_id',$request->device_token)->where('product_type', 'gift_card')->whereHas('giftCard', function($query){
                return $query->where('status', 1);
            })->pluck('id')->toArray();
        }



        $query = Cart::with('shippingMethod','seller', 'customer:id,first_name,last_name,email,email_verified_at','giftCard','product.product.product','product.sku','product.product.product.shippingMethods.shippingMethod','product.product_variations.attribute', 'product.product_variations.attribute_value.color')->whereIn('id',$cart_ids)->where('is_select', 1)->get();

        $carts = CartsResource::collection($query)->groupBy('seller_id');

        // $carts = $query->groupBy('seller_id');



        $recs = new \Illuminate\Database\Eloquent\Collection($query);

        $grouped = $recs->groupBy('seller_id');

        $shipping_cost = 0;

        $checkoutRepo = new CheckoutRepository();
        $shippingMethod = $checkoutRepo->get_active_shipping_methods()[0];
        foreach($grouped as $key => $item){
            $additional_charge = 0;
            $totalItemPriceForShipping = 0;
            $totalItemWeight = 0;
            foreach($item as $key => $data){
                if($data->product_type != "gift_card"){
                    $additional_charge += !empty($data->product)  &&  !empty($data->product->sku) ? $data->product->sku->additional_shipping:0;
                    $totalItemPriceForShipping += !empty($data) ? $data->total_price:0;
                    $totalItemWeight += !empty($data->product->sku->weight) ? $data->product->sku->weight : 0;
                }

            }
            if($shippingMethod->cost_based_on == 'Price'){
                if($totalItemPriceForShipping > 0 && $shippingMethod->cost > 0){
                    $shipping_cost += ($totalItemPriceForShipping / 100) *  $shippingMethod->cost + $additional_charge;
                }

            }elseif ($shippingMethod->cost_based_on == 'Weight'){
                if($totalItemWeight > 0 && $shippingMethod->cost > 0){
                    $shipping_cost += ($totalItemWeight / 100) *  $shippingMethod->cost + $additional_charge;
                }
            }else{
                if($shippingMethod->cost > 0){
                    $shipping_cost += $shippingMethod->cost + $additional_charge;
                }
            }
        }
        if(count($carts) > 0){
            return response()->json([
                'carts' => $carts,
                'shipping_charge' => $shipping_cost,
                'message' => trans('app.Success')
            ],200);
        }else{
            return response()->json([
                'message' => trans('app.Cart is Empty'),
                'shipping_charge' => $shipping_cost,
            ],404);
        }

    }

    /**
     * Add to cart
     *
     * @bodyParam product_id number required product sku id
     * @bodyParam qty number required product quantity
     * @bodyParam price number required product price
     * @bodyParam seller_id number required seller id
     * @bodyParam shipping_method_id number required shipping method id
     * @bodyParam product_type string required product or giftcard
     *
     * @response 201{
     *      'message' : 'product added succcessfully'
     * }
     */

    public function addToCart(Request $request){
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'product_type' => 'required',
            'seller_id' => 'required',
            'device_token' => 'required',
            "user_id" => "nullable"
        ]);

        $customer = null;
        if(!empty($request->user_id)){

            $customer = User::where('id',$request->user_id)->first();
        }

        $total_price = $request->price*$request->qty;

        if(!empty($request->user_id)){
             $product = Cart::where('user_id',$customer->id)->where('product_id',$request->product_id)->where('product_type',$request->product_type)->first();
        }else{
            $product = Cart::where('session_id',$request->device_token)->where('product_id',$request->product_id)->where('product_type',$request->product_type)->first();
        }


        if($product){
            $product->update([
                'qty' => $product->qty+$request->qty,
                'total_price' => $product->total_price + $total_price
            ]);
        }else{
            Cart::create([
                'user_id' => !empty($request->user_id) ? $request->user_id:null,
                'product_type' => ($request->product_type == 'gift_card') ? 'gift_card' : 'product',
                'product_id' => $request->product_id,
                'price' => $request->price,
                'qty' => $request->qty,
                'total_price' => $total_price,
                'seller_id' => $request->seller_id,
                'shipping_method_id' => 0,
                'sku' => null,
                'is_select' => 1,
                'session_id' => $request->device_token,
            ]);
        }

        return response()->json([
            'message' => trans('app.product added succcessfully'),
        ], 201);


    }

    public function cartForInAppPurchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'product_type' => 'required',
            'seller_id' => 'required',
            'product_sku_id' => "nullable",
            'gift_card_type' => 'nullable',
            'device_token' => 'required',
            'user_id' => "nullable"
        ]);

        $customer = null;
        if(!empty($request->user_id)){
             $customer = User::where('id',$request->user_id)->first();
        }
        $total_price = $request->price*$request->qty;

        if(!empty($request->user_id)){
             $product = Cart::where('user_id',$customer->id)->where('product_id',$request->product_id)->where('product_type',$request->product_type)->first();
        }else{
            $product = Cart::where('session_id',$request->device_token)->where('product_id',$request->product_id)->where('product_type',$request->product_type)->first();
        }

        $total_price = $request->price*$request->qty;
        if($customer){
            $product = Cart::where('user_id',$customer->id)->where('product_id',$request->product_id)->where('product_type',$request->product_type)->first();
            if($product){
                $product->delete();
            }
            $cart =  Cart::create([
                'user_id' => !empty($customer)  ? $customer->id:null,
                'product_type' => ($request->product_type == 'gift_card') ? 'gift_card' : 'product',
                'product_id' => $request->product_id,
                'price' => $request->price,
                'qty' => $request->qty,
                'total_price' => $total_price,
                'seller_id' => $request->seller_id,
                'shipping_method_id' => 0,
                'sku' => null,
                'is_select' => 1,
                'gift_card_sku' => !empty($request->product_sku_id) ? $request->product_sku_id:null,
                'gift_card_type' => !empty($request->gift_card_type) ? $request->gift_card_type:'',
                'device_token' => !empty($request->device_token) ? $request->device_token:'',
            ]);
            return response()->json([
                'message' => trans('app.product added succcessfully'),
                'cart_id' => (int) $cart->id
            ], 200);


        }else{
            return response()->json([
                'message' => trans('app.Unauthenticated')
            ]);
        }
    }

    public function cartDeleteForInAppPurchase(Request $request)
    {
        $data =  $request->validate([
            'cart_id' => "required"
        ]);


        $is_delete = Cart::where('id',$data['cart_id'])->delete();

        if($is_delete)
        {
            return response()->json([
                "status" => "success"
            ],200);
        }else{
            return response()->json([
                "status" => "failed"
            ],404);
        }
    }

    /**
     * Remove From Cart
     * @bodyParam id number required item id
     *
     * @response 203{
     *      'message' : 'removed successfully'
     * }
     *
     */

    public function removeFromCart(Request $request){
        $request->validate([
            'id' => 'required'
        ]);

        $cart = Cart::where('id', $request->id)->first();
        if($cart){
            $cart->delete();
            return response()->json([
                'message' => trans('app.removed successfully')
            ],203);
        }else{
            return response()->json([
                'message' => __('app.cart item not found')
            ], 404);
        }

    }


    /**
     *
     *
     * Quantity update
     * @bodyParam id number required item id
     * @bodyParam qty number required item quantity
     * @response 202{
     *      'message' : 'qty updated successfully'
     * }
     *
     */

    public function updateQty(Request $request){
        $request->validate([
            'id' => 'required',
            'qty' => 'required|numeric|min:1'
        ]);

        $product = Cart::where('id', $request->id)->first();
        if($product){
            $product->update([
                'qty' => $request->qty,
                'total_price' => $request->qty * $product->price
            ]);

            return response()->json([
                'message' => trans('app.qty updated successfully')
            ],202);

        }else{
            return response()->json([
                'message' => trans('app.cart item not found')
            ],404);
        }
    }

}
