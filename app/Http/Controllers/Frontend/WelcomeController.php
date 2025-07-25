<?php

namespace App\Http\Controllers\Frontend;
use Browser;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ContactService;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Services\SubscriptionService;
use Modules\Visitor\Entities\IgnoreIP;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ContactFormRequest;
use Modules\Seller\Entities\SellerProduct;
use Stevebauman\Location\Facades\Location;
use Modules\Visitor\Entities\VisitorHistory;
use Modules\FrontendCMS\Entities\DynamicPage;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\FrontendCMS\Entities\HomePageSection;
use Illuminate\Support\Facades\DB;


class WelcomeController extends Controller
{

    protected $subscribe;

    public function __construct(SubscriptionService $subscribe)
    {
        $this->subscribe = $subscribe;
        $this->middleware('maintenance_mode');
    }
    public function index(Request $request)
    {
        try {
            $ignored = IgnoreIP::where('ip', request()->ip())->first();
            $ipExists = VisitorHistory::where('date', Carbon::now()->format('y-m-d'))->where('visitors', request()->ip())->first();
            if (!$ipExists && !$ignored) {
                // Location Check
                $location = Location::get(request()->ip());
                if ($location) {
                    $country = $location->countryName ?? '';
                    $region = $location->regionName ?? '';
                    $location = $country . ", " . $region;
                } else {
                    $location = "";
                }
                VisitorHistory::create(['visitors' => request()->ip(), 'date' => Carbon::now()->format('y-m-d'), 'agent' => Browser::browserFamily() . '-' . Browser::browserVersion() . '-' . Browser::browserEngine() . '-' . Browser::platformName(), 'device' => Browser::platformName(), 'location' => $location]);
            }
            $CategoryList = collect();
            $widgets = HomePageSection::all();
            $blogs = DB::table('blog_posts')
            ->where('status', 1)
            ->where('is_approved', 1)
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();
            $previous_route = session()->get('previous_user_last_route');
            $previous_user_id = session()->get('previous_user_id');
            if ($previous_route != null) {
                session()->forget('previous_user_id');
                session()->forget('previous_user_last_route');
                return redirect($previous_route);
            } else {
                return view(theme('welcome'), compact('CategoryList', 'widgets', 'blogs'));
            }
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function get_more_products(Request $request)
    {
        if ($request->ajax()) {
            $more_products = HomePageSection::where('section_name', 'more_products')->first();
            $data['products'] = $more_products->getHomePageProductByQuery();
            return view(theme('partials._get_products'), $data);
        }
    }

    public function ajax_search_for_product(Request $request)
    {

        try {
            $productService = new ProductRepository(new SellerProduct);
            $data = $productService->searchProduct($request->all());
            return response()->json($data,200);
        }catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e;
        }
    }

    public function shopping_from_recent_viewed()
    {
        $productService = new ProductRepository(new SellerProduct);
        if (auth()->check()) {
            $sellerProductsIds = $productService->lastRecentViewinfo();
            $sellerProducts = $productService->recentViewedProducts($sellerProductsIds);
            return view(theme('pages.shopping'), compact('sellerProducts'));
        } else {
            if (session()->has('recent_viewed_products') && session()->get('recent_viewed_products') != null) {
                $sellerProducts = $productService->recentViewedProducts(session()->get('recent_viewed_products')->unique('product_id')->pluck('product_id'));
                return view(theme('pages.shopping'), compact('sellerProducts'));
            } else {
                return back();
            }
        }
    }

    public function secret_logout()
    {
        $previous_user_id = null;
        $user = User::find(auth()->id());
        if($user){
            if(session()->has('secret_logged_in_by_user')){
                $previous_user_id = session()->get('secret_logged_in_by_user');
                $user->update([
                    'secret_login' => 0,
                ]);
            }
            auth()->logout();
            session()->flush();
        }

        if ($previous_user_id != null) {
            Auth::loginUsingId($previous_user_id);
            session()->put('ip', request()->ip());
            return redirect()->route('admin.merchants_list');
        } else {
            Toastr::success(__('auth.logout_successfully'), __('common.success'));
            session()->put('ip', request()->ip());
            return redirect(url('/'));
        }
    }



    public function subscription(Request $request)
    {
        $request->validate([
            'email' => 'email|required|unique:subscriptions'
        ], [
            'email.required' => __('validation.please_fill_with_valid_email'),
            'email.unique' => __('validation.you_are_already_subscribed'),
            'email' => __('validation.please_fill_with_valid_email')
        ]);
        try {
            $response = $this->subscribe->store($request->except('_token'));
            if($response == 'subscribe_done'){
                return response()->json([
                    'msg' => 'success'
                ],201);
            }elseif($response == 'verify_link_send'){
                return response()->json([
                    'msg' => 'verify_link_send'
                ],201);
            }else{
                return response()->json([
                    'msg' => 'error'
                ],500);
            }
            LogActivity::successLog('subscription added successful.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function subscriptionVerify(Request $request){

        if(@$request->email && @$request->verify_code){
            $result = $this->subscribe->verify($request->only('email', 'verify_code'));
            if($result == 'success'){
                Toastr::success('Verified successfully.', __('common.success'));
                return redirect(url('/'));
            }elseif($result == 'invalid'){
                abort(404);
            }
        }else{
            abort(404);
        }

    }


    public function static($slug)
    {

        $pageData = DynamicPage::where('is_static', 0)->where('slug', $slug)->firstOrFail();
        if (isset($pageData)) {
            return view(theme('pages.static_page'), compact('pageData'));
        } else {
            abort(404);
        }

    }

    public function contactForm(ContactFormRequest $request, ContactService $contact)
    {
        try {

            $contact->store($request);
            $details = [
                'name' => $request->name,
                'email' => $request->email,
                'query_type' => $request->query_type,
                'message' => $request->message,
            ];
            contactMail($details);
            LogActivity::successLog('contact created successful.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function emailVerify()
    {
        $user = Auth::user();
        if (isset($user) && $user->is_verified == 0) {
            return view(theme('pages.email_verify'), compact('user'));
        } else {
            return abort(404);
        }
    }

    public function closePromotion(){
        return session()->put('close_promotion', true);
    }

    public function sendEmailViaQueueCron(){
        // Artisan::call('queue:work');
        Artisan::call('queue:work --stop-when-empty');
        return response()->json([
            'msg' => 'success'
        ],200);
    }

    public function newHome(){
        return view(theme('new_home.index'));
    }
}
