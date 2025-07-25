<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SellerController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\HomepageController;
use App\Http\Controllers\API\WishListController;
use App\Http\Controllers\Auth\API\AuthController;
use App\Http\Controllers\API\NewUserZoneController;
use App\Http\Controllers\Auth\API\ProfileController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\SupportTicketController;
use App\Http\Controllers\API\PushNotificationController;
use Modules\Product\Http\Controllers\API\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [AuthController::class, 'login']);
Route::post('/customer-login', [AuthController::class, 'customerLogin']);
Route::post('/social-login', [AuthController::class, 'socialLogin']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('get-langs',[HomepageController::class,'getLanguages']);
Route::get('get-lang',[HomepageController::class,'getLang']);
Route::get('/order/{order_number}', [OrderController::class, 'singleOrder']);

Route::get('/delivery-processes', [OrderController::class, 'deliveryProcesses']);
//cart
Route::get('/cart', [CartController::class, 'list']);
Route::post('/cart', [CartController::class, 'addToCart']);
Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
Route::post('/cart/update-qty', [CartController::class, 'updateQty']);
Route::post('in-app-cart-store',[CartController::class,'cartForInAppPurchase']);
Route::post('in-app-cart-delete',[CartController::class,'cartDeleteForInAppPurchase']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('set-lang',[HomepageController::class,'setLocale']);
    Route::post('/set-fcm-token', [PushNotificationController::class, 'setFcmToken']);
    Route::get('/order-list', [OrderController::class, 'allOrderList']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware(['api_prohibited_demo_mode']);
    Route::get('/get-user', [AuthController::class, 'getUser']);
    Route::get('/user-all-notification', [AuthController::class, 'userNotifications']);

    Route::post('/profile/update-information', [ProfileController::class, 'profileUpdate'])->middleware(['api_prohibited_demo_mode']);
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->middleware(['api_prohibited_demo_mode']);
    Route::get('/profile/address-list', [ProfileController::class, 'addressList']);
    Route::post('/profile/address-store', [ProfileController::class, 'addressStore'])->middleware(['api_prohibited_demo_mode']);
    Route::post('/profile/address-update/{id}', [ProfileController::class, 'addreddUpdate'])->middleware(['api_prohibited_demo_mode']);
    Route::post('/profile/address-delete', [ProfileController::class, 'deleteAddress'])->middleware(['api_prohibited_demo_mode']);
    Route::post('/profile/default-shipping-address', [ProfileController::class, 'defaultShippingAddress'])->middleware(['api_prohibited_demo_mode']);
    Route::post('/profile/default-billing-address', [ProfileController::class, 'defaultBillingAddress'])->middleware(['api_prohibited_demo_mode']);
    // customer delete
    Route::post('/customer-delete', [AuthController::class, 'customerDelete'])->middleware(['api_prohibited_demo_mode']);




    //checkout
    Route::get('/checkout', [CheckoutController::class, 'list']);
    Route::post('/checkout/check-price-update', [CheckoutController::class, 'checkCartPriceUpdate']);

    //coupon apply
    Route::post('/checkout/coupon-apply', [CheckoutController::class, 'couponApply']);

    //order


    Route::get('/order-pending-list', [OrderController::class, 'PendingOrderList']);
    Route::get('/order-cancel-list', [OrderController::class, 'cancelOrderList']);
    Route::post('/order-store', [OrderController::class, 'orderStore']);

    Route::post('order-store/in-app-purchase',[OrderController::class,'InAppOrderStore'])->name('InAppOrderStore');

    Route::post('/order-payment-info-store', [OrderController::class, 'paymentInfoStore']);

    Route::get('/order-to-ship', [OrderController::class, 'orderToShip']);
    Route::get('/order-to-receive', [OrderController::class, 'orderToReceive']);
    Route::get('/order-by-delivery-status', [OrderController::class, 'orderByDeliveryStatus']);
    Route::get('/order-refund-list', [OrderController::class, 'refundOrderList']);
    // track order for registerd customer
    Route::post('/order-track', [OrderController::class, 'orderTrack']);

    //order review package wise
    Route::get('/order-review', [OrderController::class, 'OrderReviewPackageWise']);
    Route::post('/order-review', [OrderController::class, 'OrderReview']);

    //waiting for review list
    Route::get('/order-review/waiting-for-review-list', [OrderController::class, 'waitingForReview']);

    // review list
    Route::get('/order-review/list', [OrderController::class, 'ReviewList']);

    //make refund
    Route::get('/order-refund/{id}', [OrderController::class, 'makeRefundData']);
    Route::post('/order-refund/store', [OrderController::class, 'refundStore']);

    //customer coupon list
    Route::get('/coupon', [CouponController::class, 'index']);
    Route::post('/coupon', [CouponController::class, 'store']);
    Route::post('/coupon/delete', [CouponController::class, 'destroy']);

    //wishlist for customer
    Route::get('/wishlist', [WishListController::class, 'index']);
    Route::post('/wishlist', [WishListController::class, 'store']);
    Route::post('/wishlist/delete', [WishListController::class, 'destroy']);

    // get customers data
    Route::get('/profile/get-customer-data', [ProfileController::class, 'getCustomerData']);

    // support ticket
    Route::get('/ticket-list', [SupportTicketController::class, 'index']);
    Route::get('/ticket-list-get-data', [SupportTicketController::class, 'getTicketsWithPaginate']);
    Route::post('/ticket-store', [SupportTicketController::class, 'store']);
    Route::get('/ticket-show/{id}', [SupportTicketController::class, 'show']);
    Route::get('/ticket/categories', [SupportTicketController::class, 'categoryList']);
    Route::get('/ticket/priorities', [SupportTicketController::class, 'priorityList']);
    Route::post('/ticket-show/reply', [SupportTicketController::class, 'replyTicket']);
});

// payment methods

Route::controller(PaymentMethodController::class)->group(function () {
    Route::get('payment-gateways', 'list');
});

// track order for guest customer
Route::post('/order-track-guest', [OrderController::class, 'orderTrack']);

// forgot password api
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordAPI'])->middleware(['api_prohibited_demo_mode']);


// seller list api
Route::get('/seller-list', [SellerController::class, 'sellerList']);
Route::get('/seller-profile/{id}', [SellerController::class, 'getSellerById']);

// filter from seller profile
Route::post('/seller/filter-by-type', [SellerController::class, 'filterByType']);
Route::post('/seller/filter-by-type-after-sort', [SellerController::class, 'filterAfterSort']);
Route::get('/seller/products/top-picks', [SellerController::class, 'topPicks']);
Route::get('/seller/product-by-id/{id}', [SellerController::class, 'productById']);


// homepage data api
Route::get('/homepage-data', [HomepageController::class, 'index']);
Route::get('/seller/products/recommended-product', [HomepageController::class, 'recomandedProduct']);

// category api
Route::get('/category-list', [CategoryController::class, 'index']);
Route::get('/product/category/{categoryId}', [CategoryController::class, 'show']);

// brand api
Route::get('products/brands', [BrandController::class, 'products']);
Route::get('products/brand/{id}', [BrandController::class, 'brandProducts']);

// search api
Route::post('/live-search', [SearchController::class, 'liveSearch']);

Route::prefix('version2')->group(function () {
    Route::get('/top-categories', [HomepageController::class, 'getTopCategoryData']);
    Route::get('/featured-brands', [HomepageController::class, 'getFeaturedBrandData']);
    Route::get('/sliders', [HomepageController::class, 'getSliderData']);
    Route::get('/top-picks', [HomepageController::class, 'getTopPickData']);
});

Route::get('marketing/new-user-zones', [NewUserZoneController::class, 'getAll']);
