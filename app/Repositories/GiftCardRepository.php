<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\GiftCard\Entities\GiftCard;
use Modules\GiftCard\Entities\GiftCardUse;
use Modules\Review\Entities\ProductReview;
use Modules\Wallet\Entities\WalletBalance;

class GiftCardRepository{

    public function getAll($sort_by, $paginate){
        $cards = GiftCard::query()->with(['galaryImages', 'shippingMethod','addGiftCard'])->latest();
        return $this->sortAndPaginate($cards, $sort_by, $paginate);
    }

    public function getForFrontend($slug){
        return GiftCard::where('status', 1)->where('sku', '!=',$slug)->latest()->get()->take(6);
    }

    public function getBySlug($slug){
        return GiftCard::with('galaryImages', 'shippingMethod')->where('sku', $slug)->where('status', 1)->firstOrFail();
    }

    public function getReviewByPage($data){
        return ProductReview::where('type', 'gift_card')->where('product_id', $data['giftcard_id'])->where('status', 1)->latest()->paginate(10);
    }

    public function getByFilterByType($data, $sort_by, $paginate){
        if (session()->has('filtergiftCard')) {
            session()->forget('filtergiftCard');
        }

        $cards = GiftCard::query();

        foreach ($data['filterType'] as $key => $filter) {

            if ($filter['filterTypeId'] == "rating" && !empty($filter['filterTypeValue'])) {
                $typeVal = $filter['filterTypeValue'][0];
                $cards = $this->productThroughRating($typeVal, $cards);

            }
            if ($filter['filterTypeId'] == "price_range") {
                $filterRepo = new FilterRepository();
                $min_price = round(end($filter['filterTypeValue'])[0])/$filterRepo->getConvertRate();
                $max_price = round(end($filter['filterTypeValue'])[1])/$filterRepo->getConvertRate();
                $cards = $this->productThroughPriceRange($min_price, $max_price, $cards);

            }

        }
        session()->put('filtergiftCard', $data);
        return $this->sortAndPaginate($cards, $sort_by, $paginate);

    }

    private function sortAndPaginate($cards, $sort_by, $paginate){
        if($sort_by != null){
            if($sort_by == 'new'){
                return $cards->where('status', 1)->orderBy('id')->paginate(($paginate != null)?$paginate:9);
            }
            if($sort_by == 'old'){
                return GiftCard::where('status', 1)->orderByDesc('id')->paginate(($paginate != null)?$paginate:9);
            }
            if($sort_by == 'alpha_asc'){
                return $cards->where('status', 1)->orderBy('name')->paginate(($paginate != null)?$paginate:9);
            }
            if($sort_by == 'alpha_desc'){
                return $cards->where('status', 1)->orderByDesc('name')->paginate(($paginate != null)?$paginate:9);
            }
            if($sort_by == 'low_to_high'){
                return $cards->where('status', 1)->orderBy('selling_price')->paginate(($paginate != null)?$paginate:9);
            }
            if($sort_by == 'high_to_low'){
                return $cards->where('status', 1)->orderByDesc('selling_price')->paginate(($paginate != null)?$paginate:9);
            }
        }else{
            return $cards->where('status', 1)->latest()->paginate(($paginate != null)?$paginate:9);
        }

        return $cards->where('status', 1)->latest()->paginate(9);
    }

    private function productThroughPriceRange($min_price, $max_price, $cards){
        return $cards->where('status', 1)->where('selling_price', '>=',$min_price)->where('selling_price', '<=', $max_price);
    }

    private function productThroughRating($typeVal, $cards){

        return $cards->where('status', 1)->where('avg_rating','>=', $typeVal);

    }

    public function getMaxPrice(){
        $orginal_price = GiftCard::where('status', 1)->max('selling_price');
        $filterRepo = new FilterRepository();
        $max_price = $filterRepo->getConvertedMax($orginal_price);
        return $max_price;
    }

    public function getMinPrice(){
        $orginal_price = GiftCard::where('status', 1)->min('selling_price');
        $filterRepo = new FilterRepository();
        $min_price = $filterRepo->getConvertedMin($orginal_price);
        return $min_price;
    }

    public function myPurchasedGiftCard($user, $data = [])
    {
        $gift_cards =  GiftCardUse::join('gift_cards','gift_cards.id','=','gift_card_uses.gift_card_id')
                                  ->join('orders','orders.id','=','gift_card_uses.order_id')
                                  ->with('order','giftCard.galaryImages', 'giftCard.shippingMethod')
                                  ->whereHas('order', function($q) use($user){
                                      $q->where('customer_id', $user->id);
                                  })->select(['gift_card_uses.*','gift_cards.name as title','gift_cards.selling_price as price','orders.created_at as order_create']);

        if(isset($data['sort_by']) &&  $data['sort_by'] == 'low_to_hight')
        {

          $gift_cards->orderBy('price','ASC');
        }elseif(isset($data['sort_by']) &&  $data['sort_by'] == 'high_to_low'){
             $gift_cards->orderBy('price','DESC');
        }elseif(isset($data['sort_by']) &&  $data['sort_by'] == 'alpha_asc'){
             $gift_cards->orderBy('title','ASC');
        }elseif(isset($data['sort_by']) &&  $data['sort_by'] == 'alpha_dsc'){
             $gift_cards->orderBy('title','DESC');
        }elseif(isset($data['sort_by']) &&  $data['sort_by'] == 'old'){
             $gift_cards->orderBy('order_create','DESC');
        }else{
            $gift_cards->orderBy('order_create','ASC');
        }

        return $gift_cards->paginate(6);
    }

    public function myPurchasedGiftCardAll($user)
    {
        return GiftCardUse::with('order','giftCard.galaryImages')->whereHas('order', function($q) use($user){
                                $q->where('customer_id', $user->id);
                            })->get();
    }

    public function myPurchasedGiftCardRedeem($data, $user)
    {

        $gift_card_use_info = GiftCardUse::with('giftCard')->findOrFail($data['gift_card_use_id']);
        if ($gift_card_use_info && $gift_card_use_info->is_used != 1) {
            $this->walletRecharge($gift_card_use_info, $user);
        }

    }

    public function myPurchasedGiftCardRedeemToWalletFromWalletRecharge($data, $user)
    {
        $gift_card_use_info = GiftCardUse::with('giftCard')->where('secret_code', $data['secret_code'])->first();
        if ($gift_card_use_info && $gift_card_use_info->is_used != 1) {
            $this->walletRecharge($gift_card_use_info, $user);
            return 'success';
        }
        elseif($gift_card_use_info && $gift_card_use_info->is_used == 1){
            return 'used';
        }else{
            return 'invalid';
        }

    }

    public function walletRecharge($gift_card_use_info, $user)
    {
       $amount = 0;
       if(!empty($gift_card_use_info->giftCard)){
            $gift = DB::table('add_gift_cards')->where('digilat_gift_id',$gift_card_use_info->giftCard->id)->first();
            if($gift)
            {
                $amount = $gift->gift_card_value;
            }
        }
        if($amount > 0){
            WalletBalance::create([
                'walletable_type' => "Modules\GiftCard\Entities\GiftCardUse",
                'walletable_id' => $gift_card_use_info->id,
                'user_id' => $user->id,
                'type' => "Deposite",
                'status' => 1,
                'amount' => round(($amount), 2),
                'payment_method' => "Gift Card Redeem",
            ]);
            $gift_card_use_info->update([
                'is_used' => 1,
                'txn_id' => $gift_card_use_info->secret_code
            ]);

        }

    }
}
