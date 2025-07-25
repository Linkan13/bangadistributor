<?php

namespace App\Http\Controllers\Auth\API;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Traits\Notification;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Models\SocialProvider;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Wallet\Entities\WalletBalance;
use Modules\Customer\Entities\CustomerAddress;
use Modules\GeneralSetting\Entities\EmailTemplateType;
use Modules\OrderManage\Entities\CustomerNotification;
use Modules\Affiliate\Repositories\AffiliateRepository;
use Modules\GeneralSetting\Entities\NotificationSetting;
use Modules\GeneralSetting\Entities\UserNotificationSetting;
use Modules\GeneralSetting\Services\NotificationSettingService;

/**
 * @group User Management
 *
 * APIs for User Management
 */

class AuthController extends Controller
{
    use Notification;
    protected $authService, $notificationSettingService;
    public function __construct(NotificationSettingService $notificationSettingService, AuthService $authService)
    {
        $this->notificationSettingService = $notificationSettingService;
        $this->authService = $authService;
    }

    /**
     * Login
     * @bodyParam email string required email or phone from user
     * @bodyParam password string required password
     *
     * @response{
     * "user": {
     *       "id": 8,
     *       "first_name": "Hafijur SPN",
     *       "last_name": "21",
     *       "username": null,
     *       "photo": null,
     *       "role_id": 4,
     *       "mobile_verified_at": null,
     *       "email": "spn21@spondonit.com",
     *       "is_verified": 0,
     *       "verify_code": null,
     *       "email_verified_at": null,
     *       "notification_preference": "mail",
     *       "is_active": 1,
     *       "avatar": null,
     *       "phone": null,
     *       "date_of_birth": null,
     *       "description": null,
     *       "secret_login": 0,
     *       "secret_logged_in_by_user": null,
     *       "created_at": "2021-06-09T11:56:56.000000Z",
     *       "updated_at": "2021-06-09T12:29:05.000000Z",
     *       "role": {
     *           "id": 4,
     *           "name": "Customer",
     *           "type": "customer",
     *           "details": null,
     *           "created_at": "2021-05-29T05:26:46.000000Z",
     *           "updated_at": null
     *       }
     *   },
     *   "token": "5|Y6PwOvfBo0W4k04SWlZV8naNLNdmXVOUnRQt4KZg",
     *   "message" : "Successfully logged In"
     * }
     */


    public function logindistributor(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_token' => "required",
        ]);

        $user = User::where('email', $request->email)->where('is_active', 1)->whereHas('role', function ($q) {
            return $q->where('type', 'distributor');
        })->first();
        if ($user && Hash::check($request->password, $user->password) && $user->role->type == 'customer') {

            $carts = Cart::where('session_id', $request->device_token)->get();

            $carts =  Cart::where('session_id', $request->device_token)->update([
                'user_id' => $user->id,
                'session_id' => ''
            ]);


            $token = $user->createToken('my_token')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Successfully logged In'
            ];

            return response($response, 200);
        } else {
            return response(['message' => 'Invalid Credintials'], 401);
        }
    }

    public function registerdistributor(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => ['required', 'string', 'max:255'],
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'The password field minimum 8 character.'
        ]);
        $user_exist = $this->authService->getRegister($request->all());
        if ($user_exist) {
            return $this->registerCustomerResponse($user_exist);
        }

        $request->validate([
            'first_name' => 'required',
            'email' => ['required', 'string', 'max:255', 'unique:users,email', 'check_unique_phone'],
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'The password field minimum 8 character.'
        ]);

        $user = $this->authService->registerdistributor($request->all());
        Cart::where('session_id', $request->device_token)->update([
            'user_id' => $user->id,
            'session_id' => ''
        ]);
        return $this->registerCustomerResponse($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_token' => "required",
        ]);
        $user = User::where('email', $request->email)->where('is_active', 1)->whereHas('role', function ($q) {
            return $q->where('type', 'customer');
        })->first();
        if (!$user) {
            $user = User::where('username', $request->email)->where('is_active', 1)->whereHas('role', function ($q) {
                return $q->where('type', 'customer');
            })->first();
        }
        if ($user && Hash::check($request->password, $user->password) && $user->role->type == 'customer') {

            $carts = Cart::where('session_id',$request->device_token)->get();

            $carts =  Cart::where('session_id',$request->device_token)->update([
                'user_id' => $user->id,
                'session_id' => ''
            ]);


            $token = $user->createToken('my_token')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Successfully logged In'
            ];

            return response($response, 200);
        } else {
            return response(['message' => 'Invalid Credintials'], 401);
        }
    }

    public function customerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_token' => "required"
        ]);
        $user = User::where('is_active', 1)->whereHas('role', function ($role) {
            return $role->where('type', 'customer');
        })
            ->where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();
        if ($user && password_verify($request->password, $user->password) && $user->role->type == 'customer') {
            $token = $user->createToken('my_token')->plainTextToken;
            Cart::where('session_id',$request->device_token)->update([
                'user_id' => $user->id,
                'session_id' => ''
            ]);
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => 'Successfully logged In'
            ];
            return response($response, 200);
        } else {
            return response(['message' => 'Invalid Credintials'], 401);
        }
    }

    public function socialLogin(Request $request)
    {
        $request->validate([
            'provider_id' => ['required'],
            'provider_name' => ['required'],
            'name' => ['nullable'],
            'email' => ['nullable'],
            'token' => 'required'
        ]);
        if ($request->provider_name == 'google') {
            $res = \Illuminate\Support\Facades\Http::get('https://oauth2.googleapis.com/tokeninfo?id_token=' . $request->token);
            if ($res->successful()) {
                return $this->getTokenBySocial($request);
            } else {
                return response()->json(['message' => 'Invalid token.'], 422);
            }
        } elseif ($request->provider_name == 'facebook') {
            $res = \Illuminate\Support\Facades\Http::get('https://graph.facebook.com/me?access_token=' . $request->token);
            if ($res->successful()) {
                return $this->getTokenBySocial($request);
            } else {
                return response()->json(['message' => 'Invalid token.'], 422);
            }
        } else {
            return response()->json(['message' => 'Invalid provider name.'], 422);
        }
    }

    /**
     * Logout user
     * @response{
     * "message": "Logged out successfully"
     * }
     */

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Register Customer
     * @bodyParam first_name string required customer first name
     * @bodyParam last_name string required customer last name
     * @bodyParam email string required email or phone from user
     * @bodyParam referral_code string nullable referral code from another user
     * @bodyParam password string required password
     * @bodyParam password_confirmation string required same as password
     * @bodyParam user_type string required customer
     *
     * @response 201{
     *
     * "user": {
     *       "first_name": "customer 5",
     *       "last_name": null,
     *       "username": null,
     *       "email": "customer5@gmail1.com",
     *       "role_id": 4,
     *       "phone": null,
     *       "updated_at": "2021-06-10T11:41:35.000000Z",
     *       "created_at": "2021-06-10T11:41:35.000000Z",
     *       "id": 9
     *   },
     *   "token": "6|PV66uUWWSNzekyWW05XqItqI9ernvqAqEkxbYGh0",
     *   "message" : "Successfully registered"
     *
     * }
     */

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => ['required', 'string', 'max:255'],
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required',
            'device_token' => "required"
        ], [
            'password.min' => 'The password field minimum 8 character.'
        ]);
        $user_exist = $this->authService->getRegister($request->all());
        if ($user_exist) {
            return $this->registerCustomerResponse($user_exist);
        }

        $request->validate([
            'first_name' => 'required',
            'email' => ['required', 'string', 'max:255', 'unique:users,email', 'check_unique_phone'],
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required',
            'device_token' => "required"
        ], [
            'password.min' => 'The password field minimum 8 character.'
        ]);
        if ($request->user_type == 'customer') {

            $user = $this->authService->register($request->all());
            Cart::where('session_id',$request->device_token)->update([
                'user_id' => $user->id,
                'session_id' => ''
            ]);
            return $this->registerCustomerResponse($user);
        } else {
            $response = ['message' => 'invalid Credintials'];
            return response()->json($response, 409);
        }
    }

    /**
     * Change Password
     * @bodyParam old_password string required old password
     * @bodyParam password string required new password
     * @bodyParam password_confirmation string required same as new password
     *
     * @response{
     *     'message' => 'password change successfully'
     * }
     */

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = $request->user();
        if ($user) {
            $response = $this->authService->changePassword($user, $request->only('old_password', 'password'));
            if ($response == 1) {
                return response()->json(['message' => 'password change successfully'], 200);
            } else {
                return response()->json(['message' => 'Invalid Credintials.'], 409);
            }
        } else {
            return response()->json(['message' => 'user not found'], 404);
        }
    }

    /**
     * Get user
     * @response{
     * "user": {
     *       "id": 9,
     *       "first_name": "customer 5",
     *       "last_name": null,
     *       "username": null,
     *       "photo": null,
     *       "role_id": 4,
     *       "mobile_verified_at": null,
     *       "email": "customer5@gmail1.com",
     *       "is_verified": 0,
     *       "verify_code": null,
     *       "email_verified_at": null,
     *       "notification_preference": "mail",
     *       "is_active": 1,
     *       "avatar": null,
     *       "phone": null,
     *       "date_of_birth": null,
     *       "description": null,
     *       "secret_login": 0,
     *       "secret_logged_in_by_user": null,
     *       "created_at": "2021-06-10T11:41:35.000000Z",
     *       "updated_at": "2021-06-10T11:41:35.000000Z",
     *       "customer_addresses": []
     *   },
     *   "message": "success"
     * }
     */

    public function getUser(Request $request)
    {
        $user = User::with('customerAddresses', 'currency', 'language')
            ->where('id', $request->user()->id)
            ->where('is_active', 1)
            ->first();
        if ($user) {
            return response()->json(['user' => $user, 'message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'user not found'], 404);
        }
    }

    /**
     * Forgot Password
     * @bodyParam email string required customers email
     * @response{
     *      "message": "Reset password link sent on your email id."
     * }
     */

    public function forgotPasswordAPI(Request $request)
    {
        $request->validate(['email' => 'required|email',]);
        $user = User::where('email', $request->email)->where('role_id', 4)->first();
        if ($user) {
            return $this->forgot($request->all());
        } else {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
    }

    /**
     * Delete Account
     * @response{
     *      "message": "success"
     * }
     */
    public function customerDelete(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($user) {
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
            $customer_orders  = Order::where('customer_id', $user->id)->pluck('id');
            $wallet = WalletBalance::where('user_id', $user->id)->pluck('id');
            if ($customer_orders->count() || $wallet->count()) {
                $user->update(['is_active' => 2]);
            } else {
                $addresses = $user->customerAddresses->pluck('id');
                CustomerAddress::destroy($addresses);
                $notifications = CustomerNotification::where('customer_id', $user->id)->pluck('id');
                CustomerNotification::destroy($notifications);
                $notification_settings = UserNotificationSetting::where('user_id', $user->id)->pluck('id');
                UserNotificationSetting::destroy($notification_settings);
                $user->delete();
            }
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'invalid'], 404);
        }
    }

    public function userNotifications(Request $request)
    {
        $user_id = $request->user()->id;
        $notifications = $this->notificationSettingService->userNotifications($user_id);

        if ($notifications) {
            return response()->json([
                'notifications' => $notifications
            ], 200);
        } else {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }
    }

    private function registerCustomerResponse($user)
    {
        $token = $user->createToken('my_token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'Successfully registered'
        ];
        return response()->json($response, 201);
    }

    private function getTokenBySocial($request)
    {
        $provider = SocialProvider::where('provider_id', $request->provider_id)->where('provider_name', $request->provider_name)->first();
        if ($provider) {
            $user = User::where('id', $provider->user_id)->where('is_active', 1)->first();
            if ($user) {
                $token = $user->createToken('my_token')->plainTextToken;
                $response = [
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Successfully logged In'
                ];
                return response($response, 200);
            } else {
                return response()->json(['message' => 'Your Account is Disabled.'], 422);
            }
        } else {
            $exsist = User::where('email', $request->email)->first();
            if (!$exsist) {
                $newUser = User::create([
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make("verystrongpass1234"),
                    'role_id' => 4,
                    'is_verified' => 1,
                    'currency_id' => app('general_setting')->currency,
                    'lang_code' => app('general_setting')->language_code,
                    'currency_code' => app('general_setting')->currency_code,
                ]);

                SocialProvider::create([
                    'user_id' => $newUser->id,
                    'provider_id' => $request->provider_id,
                    'provider_name' => $request->provider_name,
                ]);

                //affiliate user
                if (isModuleActive('Affiliate')) {
                    $affiliateRepo = new AffiliateRepository();
                    $affiliateRepo->affiliateUser($newUser->id);
                }

                // User Notification Setting Create
                (new UserNotificationSetting)->createForRegisterUser($newUser->id);
                $this->typeId = EmailTemplateType::where('type', 'register_email_template')->first()->id; //register email templete typeid
                $this->adminNotificationUrl = '/customer/active-customer-list';
                $this->routeCheck = 'cusotmer.list.get-data';
                $notification = NotificationSetting::where('slug', 'register')->first();
                if ($notification) {
                    $this->notificationSend($notification->id, $newUser->id);
                }
                $token = $newUser->createToken('my_token')->plainTextToken;
                $response = [
                    'user' => $newUser,
                    'token' => $token,
                    'message' => 'Successfully logged In'
                ];
                return response($response, 200);
            } else {
                return response()->json(['message' => 'Email Already Taken By Normal Registration.'], 422);
            }
        }
    }

    private function forgot($user)
    {
        Password::sendResetLink($user);
        return response()->json(["message" => 'Reset password link sent on your email id.']);
    }
}
