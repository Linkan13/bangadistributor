<?php

namespace App\Traits;

use PDF;
use App\Models\User;
use App\Jobs\SendmailJob;
use App\Mail\TestSmptMail;
use App\Mail\SendQueueMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Modules\GeneralSetting\Entities\EmailTemplateType;

trait SendMail
{

    public function sendNotificationByMail($typeId, $user, $notificationSetting, $relatable_id = null, $relatable_type = null, $order_tracking_number = null)
    {
        $email_template = EmailTemplate::where('type_id', $typeId)->where('relatable_id', $relatable_id)->where('relatable_type', $relatable_type)->where('is_active', 1)->first();

        if ($email_template) {
            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, $order_tracking_number, $notificationSetting->message);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, $order_tracking_number, $notificationSetting->message);
                    $message = (string) view('emails.mail', $datas);
                    $this->phpMailData($user->email, $email_template->subject, $message);

                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
            }
        }
    }

    public function orderCancelMailSend($type_id, $order)
    {
        $email_template = EmailTemplate::where('type_id', $type_id)->where('is_active', 1)->first();
        if($email_template)
        {
            if (app('general_setting')->mail_protocol == "smtp") {
                $datas = $this->mailData($email_template);
                    Mail::to($order->customer_email)->queue(new SendQueueMail($datas));
                    return true;
            }else{

            }
        }
    }

    public function sendOtpByMail($user, $otp)
    {

        $email_template = EmailTemplate::where('type_id', 35)->where('is_active', 1)->first();
        $email = $user->customer_email;
        if ($email_template) {

            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                     $datas = $this->otpMailData($email_template, $user->name, $email, $otp);
                     Mail::to($user->email)->queue(new SendQueueMail($datas));
                     return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->otpMailData($email_template, $user->first_name, $user->email, $otp);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function sendLoginOtpByMail($user, $otp)
    {
        $email_template = EmailTemplate::where('type_id', 37)->where('is_active', 1)->first();
        if ($email_template) {
            try {

                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->otpMailData($email_template, $user->first_name, $user->email, $otp);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->otpMailData($email_template, $user->first_name, $user->email, $otp);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function sendPasswordResetOtpByMail($user, $otp)
    {
        $email_template = EmailTemplate::where('type_id', 38)->where('is_active', 1)->first();
        if ($email_template) {
            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->otpMailData($email_template, $user->first_name, $user->email, $otp);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->otpMailData($email_template, $user->first_name, $user->email, $otp);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function sendOtpByMailForSeller($user, $otp)
    {
        $email_template = EmailTemplate::where('type_id', 35)->where('is_active', 1)->first();
        if ($email_template) {
            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->otpMailData($email_template, $user->name, $user->email, $otp);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->otpMailData($email_template, $user->name, $user->email, $otp);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function sendOtpByMailForOrder($user, $otp)
    {
        $email_template = EmailTemplate::where('type_id', 36)->where('is_active', 1)->first();
        $email = $user->customer_email;
        if ($email_template) {
            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->mailData($email_template, $user->name, $email, $otp);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->mailData($email_template, $user->name, $email, $otp);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function sendSupportTicketMail($user, $supportTicketMessage)
    {
        $email_template = EmailTemplate::where('type_id', 22)->where('is_active', 1)->first();
        if ($email_template) {
            try {

                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, $supportTicketMessage);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, $supportTicketMessage);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
            }
        }
    }
    public function sendVerificationMail($user, $supportTicketMessage)
    {
        $email_template = EmailTemplate::where('type_id', 23)->where('is_active', 1)->first();
        if ($email_template) {
            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, null, null, null, $supportTicketMessage);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, null, null, null, $supportTicketMessage);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
            }
        }
    }

    public function sendSellerVerificationMail($user, $supportTicketMessage)
    {
        $email_template = EmailTemplate::where('type_id', 39)->where('is_active', 1)->first();
        if ($email_template) {
            try {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, null, null, null, $supportTicketMessage);
                    Mail::to($user->email)->queue(new SendQueueMail($datas));
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->mailData($email_template, $user->first_name, $user->email, null, null, null, $supportTicketMessage);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($user->email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                LogActivity::errorLog($e->getMessage());
            }
        }
    }

    function sendMailWithTemplate($to, $array, $mailPath, $template)
    {
        try {
            $general_setting = DB::table('general_settings')->select('mail_protocol','email')->first();
            if ($general_setting->mail_protocol == "smtp") {
                Mail::to($to)->queue(new $mailPath($array));
            } elseif ($general_setting->mail_protocol == "sendmail") {
                $message = (string) view($template, compact('array'));
                if(config('queue.default') == 'sync'){
                    return $this->phpMailData($to, $array['subject'], $message);
                }else{
                    dispatch(new SendmailJob($to, $array['subject'], $message));
                    return true;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }

    public function sendMailTest($to, $subject, $body)
    {

        try {
            if (app('general_setting')->mail_protocol == "smtp") {
                $attribute = [
                    'from' => env('MAIL_FROM_ADDRESS'),
                    'subject' => $subject,
                    'content' => $body
                ];

                Mail::to($to)->send(new TestSmptMail($attribute));
                return true;
            } elseif (app('general_setting')->mail_protocol == "sendmail") {
                $datas = [
                    'from' => env('MAIL_FROM_ADDRESS'),
                    'subject' => $subject,
                    'body' => $body
                ];
                $message = (string) view("emails.mail", $datas);
                if(config('queue.default') == 'sync'){
                    return $this->phpMailData($to, $subject, $message);
                }else{
                    dispatch(new SendmailJob($to, $subject, $message));
                    return true;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return 'failed';
        }
    }

    function sendInvoiceMail($order_number, $order)
    {
        try {
            $email_template = EmailTemplate::where('type_id', 1)->where('is_active', 1)->first();
            if ($email_template && $email_template->is_active == 1) {
                if (app('general_setting')->mail_protocol == "smtp") {
                    $path = public_path('/invoice/order-'.$order->id.'.pdf');
                    $pdf = PDF::loadView(theme('pages.profile.order_pdf'), compact('order'))->save($path);
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailInvoiceData($email_template, $order->customer->first_name, $order->customer_email, $order);
                            $datas['attach'] = $path;
                            Mail::to($order->customer_email)->queue(new SendQueueMail($datas));
                        } else {
                            $datas = $this->mailInvoiceData($email_template, $order->guest_info->billing_name, $order->guest_info->billing_email, $order);
                            $datas['attach'] = $path;
                            Mail::to($order->guest_info->billing_email)->queue(new SendQueueMail($datas));
                        }
                    }
                    if (in_array("admin", json_decode($email_template->reciepnt_type))) {
                        foreach ($order->packages as $key => $package) {
                            if ($package->seller->email) {
                                $datas = $this->mailData($email_template, $package->seller->first_name, $package->seller->email, $package->package_code);
                                $datas['attach'] = $path;
                                Mail::to($package->seller->email)->queue(new SendQueueMail($datas));
                            }
                        }
                    }
                    if (in_array("seller", json_decode($email_template->reciepnt_type))) {
                        foreach ($order->packages as $key => $package) {
                            if ($package->seller->email) {
                                $datas = $this->mailData($email_template, $package->seller->first_name, $package->seller->email, $package->package_code);
                                $datas['attach'] = $path;
                                Mail::to($package->seller->email)->queue(new SendQueueMail($datas));
                            }
                        }
                    }
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    $datas = $this->mailData($email_template, $order->customer->first_name, $order->customer_email, $order->order_number);
                    $message = (string) view('emails.mail', $datas);
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($order->customer_email, $email_template->subject, $message);
                    }else{
                        dispatch(new SendmailJob($order->customer_email, $email_template->subject, $message));
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }
    function sendOrderRefundInfoUpdateMail($order, $type_id)
    {
        try {
            $email_template = EmailTemplate::where('type_id', $type_id)->where('is_active', 1)->first();
            if ($email_template && $email_template->is_active == 1) {
                if (app('general_setting')->mail_protocol == "smtp") {
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailData($email_template, $order->customer->first_name, $order->customer_email, $order->order_number);
                            Mail::to($order->customer_email)->queue(new SendQueueMail($datas));
                        } else {
                            $datas = $this->mailData($email_template, $order->guest_info->billing_name, $order->guest_info->billing_email, $order->order_number);
                            Mail::to($order->guest_info->billing_email)->queue(new SendQueueMail($datas));
                        }
                    }
                    if (in_array("seller", json_decode($email_template->reciepnt_type))) {
                        foreach ($order->packages as $key => $package) {
                            if ($package->seller->email) {
                                $datas = $this->mailData($email_template, $package->seller->first_name, $package->seller->email, $package->package_code);
                                Mail::to($package->seller->email)->queue(new SendQueueMail($datas));
                            }
                        }
                    }
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailData($email_template, $order->customer->first_name, $order->customer_email, $order->order_number);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                $this->phpMailData($order->customer_email, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($order->customer_email, $email_template->subject, $message));
                            }
                        } else {
                            $datas = $this->mailData($email_template, $order->guest_info->billing_name, $order->guest_info->billing_email, $order->order_number);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                $this->phpMailData($order->guest_info->billing_email, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($order->guest_info->billing_email, $email_template->subject, $message));
                            }
                        }
                    }
                    if (in_array("seller", json_decode($email_template->reciepnt_type))) {
                        foreach ($order->packages as $key => $package) {
                            if ($package->seller->email) {
                                $datas = $this->mailData($email_template, $package->seller->first_name, $package->seller->email, $package->package_code);
                                $message = (string) view('emails.mail', $datas);
                                if(config('queue.default') == 'sync'){
                                    $this->phpMailData($package->seller->email, $email_template->subject, $message);
                                }else{
                                    dispatch(new SendmailJob($package->seller->email, $email_template->subject, $message));
                                }
                            }
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }
    function sendOrderRefundorDeliveryProcessMail($order, $relatable_type, $relatable_id)
    {
        try {
            $email_template = EmailTemplate::where('relatable_type', $relatable_type)->where('relatable_id', $relatable_id)->first();
            if ($email_template && $email_template->is_active == 1) {
                if (app('general_setting')->mail_protocol == "smtp") {
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailData($email_template, $order->customer->first_name, $order->customer_email, $order->order_number);
                            Mail::to($order->customer_email)->queue(new SendQueueMail($datas));
                        } else {
                            $datas = $this->mailData($email_template, $order->guest_info->billing_name, $order->guest_info->billing_email, $order->order_number);
                            Mail::to($order->guest_info->billing_email)->queue(new SendQueueMail($datas));
                        }
                    }
                    if (in_array("seller", json_decode($email_template->reciepnt_type))) {
                        foreach ($order->packages as $key => $package) {
                            if ($package->seller->email) {
                                $datas = $this->mailData($email_template, $package->seller->first_name, $package->seller->email, $package->package_code);
                                Mail::to($package->seller->email)->queue(new SendQueueMail($datas));
                            }
                        }
                    }
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailData($email_template, $order->customer->first_name, $order->customer_email, $order->order_number);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                $this->phpMailData($order->customer_email, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($order->customer_email, $email_template->subject, $message));
                            }
                        } else {
                            $datas = $this->mailData($email_template, $order->guest_info->billing_name, $order->guest_info->billing_email, $order->order_number);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                $this->phpMailData($order->guest_info->billing_email, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($order->guest_info->billing_email, $email_template->subject, $message));
                            }
                        }
                    }
                    if (in_array("seller", json_decode($email_template->reciepnt_type))) {
                        foreach ($order->packages as $key => $package) {
                            if ($package->seller->email) {
                                $datas = $this->mailData($email_template, $package->seller->first_name, $package->seller->email, $package->package_code);
                                $message = (string) view('emails.mail', $datas);
                                if(config('queue.default') == 'sync'){
                                    $this->phpMailData($package->seller->email, $email_template->subject, $message);
                                }else{
                                    dispatch(new SendmailJob($package->seller->email, $email_template->subject, $message));
                                }
                            }
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }
    function sendGiftCardSecretCodeMail($order, $to_mail, $gift_card, $secret_code)
    {
        try {
            $email_template = EmailTemplate::where('type_id', 15)->where('is_active', 1)->first();
            if ($email_template && $email_template->is_active == 1) {
                if (app('general_setting')->mail_protocol == "smtp") {
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailDataGiftCard($email_template, $order->customer->first_name, $to_mail, $order->order_number, $secret_code, $gift_card->name);
                            Mail::to($to_mail)->queue(new SendQueueMail($datas));
                        } else {
                            $datas = $this->mailDataGiftCard($email_template, $order->guest_info->shipping_name, $to_mail, $order->order_number, $secret_code, $gift_card->name);
                            Mail::to($to_mail)->queue(new SendQueueMail($datas));
                        }
                    }
                    return true;
                } elseif (app('general_setting')->mail_protocol == "sendmail") {
                    if (in_array("customer", json_decode($email_template->reciepnt_type))) {
                        if ($order->customer_id) {
                            $datas = $this->mailDataGiftCard($email_template, $order->customer->first_name, $to_mail, $order->order_number, $secret_code, $gift_card->name);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                return $this->phpMailData($to_mail, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($to_mail, $email_template->subject, $message));
                                return true;
                            }
                        } else {
                            $datas = $this->mailData($email_template, $order->guest_info->shipping_name, $to_mail, $order->order_number, $secret_code, $gift_card->name);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                return $this->phpMailData($to_mail, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($to_mail, $email_template->subject, $message));
                                return true;
                            }
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }
    // send digital file
    function sendDigitalFileMail($to_mail, $download_link, $data = null)
    {
        try {
            $email_template = EmailTemplate::where('type_id', 43)->where('is_active', 1)->first();
            if($email_template){
                if(@$data['customer_id']){
                    $customer = User::find($data['customer_id']);
                    $customer_name = $customer->first_name;
                }else{
                    $customer_name = '';
                }
                $link = "'<a href='" .  $download_link . "'>Click Here to download</a>'";
                $datas = $this->mailData($email_template, $customer_name, $to_mail, null, null, null, null, $link);
                if (app('general_setting')->mail_protocol == "smtp") {
                    Mail::to($to_mail)->queue(new SendQueueMail($datas));
                    return true;
                }elseif(app('general_setting')->mail_protocol == "sendmail"){
                    if(config('queue.default') == 'sync'){
                        return $this->phpMailData($to_mail, $datas["title"], $datas["body"]);
                    }else{
                        dispatch(new SendmailJob($to_mail, $datas["title"], $datas["body"]));
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }
    // send newsletter email verify mail
    public function sendNewsletterVerifyMail($data){
        try {

            $verify_link = url('').'/subscription/email-verify?email='.$data->email.'&verify_code='.$data->verify_code;
            $verify_link = "<a href='" .  $verify_link . "'>Click Here</a>";
            $email_template = EmailTemplate::where('type_id', 42)->where('is_active', 1)->first();
            $datas = $this->mailData($email_template, '', $data->email, '', null, null,$verify_link);
            if (app('general_setting')->mail_protocol == "smtp") {
                Mail::to($data->email)->queue(new SendQueueMail($datas));
                return true;
            }elseif(app('general_setting')->mail_protocol == "sendmail"){
                $message = (string) view('emails.mail', $datas);
                if(config('queue.default') == 'sync'){
                    return $this->phpMailData($data->email, $datas['title'], $message);
                }else{
                    dispatch(new SendmailJob($data->email, $datas['title'], $message));
                    return true;
                }
            }
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
        }
    }
    public function mailData($email_template, $to_name, $to_mail, $order_tracking_number, $custom_message = null, $RESET_URL = null,$VERIFICATION_LINK = null, $DIGITAL_FILE_LINK = null)
    {
        $datas["email"] = app('general_setting')->email;
        $datas["title"] = $email_template->subject;
        $datas['from'] = env('MAIL_FROM_ADDRESS');
        $datas["body"] = $email_template->value;
        $datas["body"] = str_replace("{SECRET_CODE}", $order_tracking_number, $datas["body"]);
        $datas["body"] = str_replace("{USER_FIRST_NAME}", $to_name, $datas["body"]);
        $datas["body"] = str_replace("{USER_EMAIL}", $to_mail, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_SIGNATURE}", app('general_setting')->mail_signature, $datas["body"]);
        $datas["body"] = str_replace("{ORDER_TRACKING_NUMBER}", $order_tracking_number, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_FOOTER}", $email_template->footer, $datas["body"]);
        $datas["body"] = str_replace("{WEBSITE_NAME}", app('general_setting')->site_title, $datas["body"]);
        $datas["body"] = str_replace("{CUSTOM_MESSAGE}", $custom_message, $datas["body"]);
        $datas["body"] = str_replace("{RESET_URL}", $RESET_URL, $datas["body"]);
        $datas["body"] = str_replace("{VERIFICATION_LINK}", $VERIFICATION_LINK, $datas["body"]);
        $datas["body"] = str_replace("{DIGITAL_FILE_LINK}", $DIGITAL_FILE_LINK, $datas["body"]);
        return $datas;
    }

    public function otpMailData($email_template,$to_name,$to_mail,$otp)
    {

        $datas["email"] = app('general_setting')->email;
        $datas["title"] = $email_template->subject;
        $datas['from'] = env('MAIL_FROM_ADDRESS');
        $datas["body"] = $email_template->value;
        $datas["body"] = str_replace("{USER_FIRST_NAME}", $to_name, $datas["body"]);
        $datas["body"] = str_replace("{USER_EMAIL}", $to_mail, $datas["body"]);
        $datas["body"] = str_replace("{OTP}", $otp, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_SIGNATURE}", app('general_setting')->mail_signature, $datas["body"]);
        return $datas;
    }

    public function mailInvoiceData($email_template, $to_name, $to_mail, $order)
    {
        $datas["email"] = app('general_setting')->email;
        $datas["title"] = $email_template->subject;
        $datas["body"] = $email_template->value;
        $datas["body"] = str_replace("{USER_FIRST_NAME}", $to_name, $datas["body"]);
        $datas["body"] = str_replace("{USER_EMAIL}", $to_mail, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_SIGNATURE}", app('general_setting')->mail_signature, $datas["body"]);
        $datas["body"] = str_replace("{ORDER_TRACKING_NUMBER}", $order->order_number, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_FOOTER}", $email_template->footer, $datas["body"]);
        $datas["body"] = str_replace("{WEBSITE_NAME}", app('general_setting')->site_title, $datas["body"]);
        $datas["body"] = str_replace("{RECIEVER_EMAIL}", @$order->shipping_address->email, $datas["body"]);
        $datas["body"] = str_replace("{RECIEVER_PHONE}", @$order->shipping_address->phone, $datas["body"]);
        $datas["body"] = str_replace("{RECIEVER_ADDRESS}", @$order->shipping_address->address, $datas["body"]);
        $datas["body"] = str_replace("{RECIEVER_CITY}", @$order->shipping_address->city->name, $datas["body"]);
        $datas["body"] = str_replace("{RECIEVER_STATE}", @$order->shipping_address->state->name, $datas["body"]);
        $datas["body"] = str_replace("{RECIEVER_COUNTRY}", @$order->shipping_address->country->name, $datas["body"]);
        $datas["inv_details"] = (string) view(theme('pages.profile.order_pdf'), compact('order'));
        return $datas;
    }

    public function phpMailData($to, $subject, $message)
    {
        try {
            $headers = "From:  ".env('SENDER_NAME') ." <".env('SENDER_MAIL').">"  . " \r\n";
            $headers .= "Reply-To: " . app('general_setting')->email . " \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
            return mail($to, $subject, $message, $headers);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return false;
        }
    }

    public function mailDataGiftCard($email_template, $to_name, $to_mail, $order_tracking_number, $secret_code,$gift_card_name)
    {
        $datas["email"] = app('general_setting')->email;
        $datas["title"] = $email_template->subject;
        $datas["body"] = $email_template->value;
        $datas["body"] = str_replace("{USER_FIRST_NAME}", $to_name, $datas["body"]);
        $datas["body"] = str_replace("{USER_EMAIL}", $to_mail, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_SIGNATURE}", app('general_setting')->mail_signature, $datas["body"]);
        $datas["body"] = str_replace("{ORDER_TRACKING_NUMBER}", $order_tracking_number, $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_FOOTER}", $email_template->footer, $datas["body"]);
        $datas["body"] = str_replace("{WEBSITE_NAME}", app('general_setting')->site_title, $datas["body"]);
        $datas["body"] = str_replace("{SECRET_CODE}", $secret_code, $datas["body"]);
        $datas["body"] = str_replace("{GIFT_CARD_NAME}", $gift_card_name, $datas["body"]);
        return $datas;
    }

    public function phpMailDataGiftCard($to, $subject, $message)
    {
        try {
            $headers = "From:  ".env('SENDER_NAME') ." <".env('SENDER_MAIL').">"  . " \r\n";
            $headers .= "Reply-To: " . app('general_setting')->email . " \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
            return mail($to, $subject, $message, $headers);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return false;
        }
    }
    public function phpMailDigitalfile($to, $subject, $message)
    {
        try {
            $headers = "From:  ".env('SENDER_NAME') ." <".env('SENDER_MAIL').">"  . " \r\n";
            $headers .= "Reply-To: " . app('general_setting')->email . " \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
            return mail($to, $subject, $message, $headers);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return false;
        }
    }

    public function userActivationMailSend($type, $user)
    {
        $emailType = EmailTemplateType::where('type',$type)->first();
        if($emailType){
            $email_template = EmailTemplate::where('type_id', $emailType->id)->where('is_active', 1)->first();
            if ($email_template) {
                try {
                    if (app('general_setting')->mail_protocol == "smtp") {
                        $datas = $this->activationMailData($email_template, $user);
                        Mail::to($user->email)->queue(new SendQueueMail($datas));
                        return true;
                    } elseif (app('general_setting')->mail_protocol == "sendmail") {
                        $datas = $this->activationMailData($email_template,$user);
                        $message = (string) view('emails.mail', $datas);
                        if(config('queue.default') == 'sync'){
                            return $this->phpMailData($user->email, $email_template->subject, $message);
                        }else{
                            dispatch(new SendmailJob($user->email, $email_template->subject, $message));
                            return true;
                        }
                    } else {
                        return false;
                    }
                } catch (\Exception $e) {
                    LogActivity::errorLog($e->getMessage());
                    return false;
                }
            }
            return false;
        }
        return false;
    }


    public function newUserRegistradEmailSend($type, $user)
    {
        $admin = User::where('id',1)->first();
        if($admin){
            $emailType = EmailTemplateType::where('type',$type)->first();
            if($emailType){
                $email_template = EmailTemplate::where('type_id', $emailType->id)->where('is_active', 1)->first();
                if ($email_template) {
                    try {
                        if (app('general_setting')->mail_protocol == "smtp") {
                            $datas = $this->registrationMailData($email_template, $user);
                            Mail::to($admin->email)->queue(new SendQueueMail($datas));
                            return true;
                        } elseif (app('general_setting')->mail_protocol == "sendmail") {
                            $datas = $this->registrationMailData($email_template,$user);
                            $message = (string) view('emails.mail', $datas);
                            if(config('queue.default') == 'sync'){
                                return $this->phpMailData($admin->email, $email_template->subject, $message);
                            }else{
                                dispatch(new SendmailJob($admin->email, $email_template->subject, $message));
                                return true;
                            }
                        } else {
                            return false;
                        }
                    } catch (\Exception $e) {
                        LogActivity::errorLog($e->getMessage());
                        return false;
                    }
                }
                return false;
            }
            return false;
        }
        return false;
    }

    public function activationMailData($email_template,$user){
        $datas["email"] = app('general_setting')->email;
        $datas["title"] = $email_template->subject;
        $datas['from'] = env('MAIL_FROM_ADDRESS');
        $datas["body"] = $email_template->value;
        $datas["body"] = str_replace("{USER_FIRST_NAME}", $user->name, $datas["body"]);
        $datas["body"] = str_replace("{APP_NAME}", config('app.name'), $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_SIGNATURE}", app('general_setting')->mail_signature, $datas["body"]);
        return $datas;
    }


    public function registrationMailData($email_template,$user)
    {
        $datas["email"] = app('general_setting')->email;
        $datas["title"] = $email_template->subject;
        $datas['from'] = env('MAIL_FROM_ADDRESS');
        $datas["body"] = $email_template->value;
        $datas["body"] = str_replace("{CUSTOMER_NAME}", $user->name, $datas["body"]);
        $datas["body"] = str_replace("{CUSTOMER_EMAIL}", $user->email, $datas["body"]);
        $datas["body"] = str_replace("{APP_NAME}", config('app.name'), $datas["body"]);
        $datas["body"] = str_replace("{EMAIL_SIGNATURE}", app('general_setting')->mail_signature, $datas["body"]);
        return $datas;
    }
}
