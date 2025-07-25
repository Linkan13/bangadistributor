<?php

namespace Modules\Marketing\Repositories;

use App\Models\UsedMedia;
use Illuminate\Support\Str;
use Modules\Marketing\Entities\Coupon;
use Modules\Product\Entities\Category;
use Modules\Seller\Entities\SellerProduct;
use Modules\Marketing\Entities\NewUserZone;
use Modules\Marketing\Entities\NewUserZoneCoupon;
use Modules\Marketing\Entities\NewUserZoneProduct;
use Modules\Marketing\Entities\NewUserZoneCategory;
use Modules\Appearance\Entities\HeaderNewUserZonePanel;
use Modules\Marketing\Transformers\NewUserZoneResource;
use Modules\Marketing\Entities\NewUserZoneCouponCategory;
use App\Repositories\NewUserZoneRepository as RepositoriesNewUserZoneRepository;

class NewUserZoneRepository
{


    public function getAll()
    {
        $user = auth()->user();
        if ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') {
            return NewUserZone::all();
        } elseif ($user->role->type == 'seller') {
            return NewUserZone::where('created_by', $user->id)->get();
        } else {
            return [];
        }
    }

    public function store($data)
    {
        $new_user_zone = new NewUserZone();
        if (isModuleActive('FrontendMultiLang')) {
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title'][auth()->user()->lang_code]) . '-' . Str::random(5));
        }else{
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title']) . '-' . Str::random(5));
        }
        $data['title_show'] = $data['title_show'] ?? 0;
        if (isModuleActive('FrontendMultiLang')) {
         $new_user_zone->fill($data)->save();
        }else{
            $new_user_zone->setTranslation('title','en',$data['title']);
            $new_user_zone->setTranslation('sub_title','en',$data['sub_title']);
            $new_user_zone->setTranslation('product_navigation_label','en',$data['product_navigation_label']);
            $new_user_zone->setTranslation('category_navigation_label','en',$data['category_navigation_label']);
            $new_user_zone->setTranslation('coupon_navigation_label','en',$data['coupon_navigation_label']);
            $new_user_zone->setTranslation('product_slogan','en',$data['product_slogan']);
            $new_user_zone->setTranslation('category_slogan','en',$data['category_slogan']);
            $new_user_zone->setTranslation('coupon_slogan','en',$data['coupon_slogan']);
            $new_user_zone->background_color = $data['background_color'];
            $new_user_zone->banner_image = $data['banner_image'];
            $new_user_zone->slug  = $data['slug'];
            $new_user_zone->text_color = isset($data['text_color']) ? $data['text_color']:'';
            $new_user_zone->is_featured = isset($data['is_featured']) ? $data['is_featured']:0;
            $new_user_zone->status = isset($data['status']) ? $data['status']:0;
            $new_user_zone->title_show = isset($data['title_show']) ? $data['title_show']:0;
            $new_user_zone->save();
        }

        if (isset($data['new_user_zone_banner_image'])) {
            UsedMedia::create([
                'media_id' => $data['new_user_zone_banner_image'],
                'usable_id' => $new_user_zone->id,
                'usable_type' => get_class($new_user_zone),
                'used_for' => 'banner_image'
            ]);
        }
        if ($new_user_zone) {
            foreach ($data['product'] as $key => $product) {
                NewUserZoneProduct::create([
                    'new_user_zone_id' => $new_user_zone->id,
                    'seller_product_id' => $product
                ]);
            }
            foreach ($data['category'] as $key => $id) {
                NewUserZoneCategory::create([
                    'new_user_zone_id' => $new_user_zone->id,
                    'category_id' => $id,
                    'position' => $key + 1
                ]);
            }
            if (isset($data['coupon'])) {
                NewUserZoneCoupon::create([
                    'new_user_zone_id' => $new_user_zone->id,
                    'coupon_id' => $data['coupon']
                ]);
            }
            foreach ($data['coupon_category'] as $key => $category_id) {
                NewUserZoneCouponCategory::create([
                    'new_user_zone_id' => $new_user_zone->id,
                    'category_id' => $category_id,
                    'position' => $key + 1
                ]);
            }
        }
        return true;
    }
    public function update($data, $id)
    {
        $new_user_zone = NewUserZone::findOrFail($id);
        if (isModuleActive('FrontendMultiLang')) {
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title'][auth()->user()->lang_code]) . '-' . Str::random(5));
        }else{
            $data['slug'] = strtolower(str_replace(' ', '-', $data['title']) . '-' . Str::random(5));
        }
        $data['title_show'] = $data['title_show'] ?? 0;
        if (isModuleActive('FrontendMultiLang')) {
            $new_user_zone->fill($data)->save();
           }else{
               $new_user_zone->setTranslation('title','en',$data['title']);
               $new_user_zone->setTranslation('sub_title','en',$data['sub_title']);
               $new_user_zone->setTranslation('product_navigation_label','en',$data['product_navigation_label']);
               $new_user_zone->setTranslation('category_navigation_label','en',$data['category_navigation_label']);
               $new_user_zone->setTranslation('coupon_navigation_label','en',$data['coupon_navigation_label']);
               $new_user_zone->setTranslation('product_slogan','en',$data['product_slogan']);
               $new_user_zone->setTranslation('category_slogan','en',$data['category_slogan']);
               $new_user_zone->setTranslation('coupon_slogan','en',$data['coupon_slogan']);
               $new_user_zone->background_color = $data['background_color'];
               $new_user_zone->banner_image = $data['banner_image'];
               $new_user_zone->slug  = $data['slug'];
               $new_user_zone->text_color = isset($data['text_color']) ? $data['text_color']:'';
               $new_user_zone->is_featured = isset($data['is_featured']) ? $data['is_featured']:0;
               $new_user_zone->status = isset($data['status']) ? $data['status']:0;
               $new_user_zone->title_show = isset($data['title_show']) ? $data['title_show']:0;
               $new_user_zone->save();
           }
        if ($new_user_zone) {
            $old_products = NewUserZoneProduct::where('new_user_zone_id', $id)->whereNotIn('seller_product_id', $data['product'])->pluck('id');
            NewUserZoneProduct::destroy($old_products);
            foreach ($data['product'] as $key => $product) {
                $zone = NewUserZoneProduct::where('new_user_zone_id', $id)->where('seller_product_id', $product)->first();
                if ($zone != null) {
                    $zone->update([
                        'new_user_zone_id' => $id,
                        'seller_product_id' => $product
                    ]);
                } else {
                    NewUserZoneProduct::create([
                        'new_user_zone_id' => $id,
                        'seller_product_id' => $product
                    ]);
                }
            }
            $old_categories = NewUserZoneCategory::where('new_user_zone_id', $id)->whereNotIn('category_id', $data['category'])->pluck('id');
            NewUserZoneCategory::destroy($old_categories);
            foreach ($data['category'] as $key => $category_id) {
                $category = NewUserZoneCategory::where('new_user_zone_id', $id)->where('category_id', $category_id)->first();
                if ($category != null) {
                    $category->update([
                        'category_id' => $category_id,
                        'position' => $key + 1
                    ]);
                } else {
                    NewUserZoneCategory::create([
                        'new_user_zone_id' => $id,
                        'category_id' => $category_id,
                        'position' => $key + 1
                    ]);
                }
            }
            if (isset($data['coupon'])) {
                $updateNewUserZoneCoupon = NewUserZoneCoupon::where('new_user_zone_id', $id)->update([
                    'coupon_id' => $data['coupon']
                ]);
                if (!$updateNewUserZoneCoupon) {
                    NewUserZoneCoupon::create([
                        'new_user_zone_id' =>  $id,
                        'coupon_id' => $data['coupon']
                    ]);
                }
            }
            $old_coupon_categories = NewUserZoneCouponCategory::where('new_user_zone_id', $id)->whereNotIn('category_id', $data['coupon_category'])->pluck('id');
            NewUserZoneCouponCategory::destroy($old_coupon_categories);
            foreach ($data['coupon_category'] as $key => $category_id) {
                $category = NewUserZoneCouponCategory::where('new_user_zone_id', $id)->where('category_id', $category_id)->first();
                if ($category != null) {
                    $category->update([
                        'category_id' => $category_id,
                        'position' => $key + 1
                    ]);
                } else {
                    NewUserZoneCouponCategory::create([
                        'new_user_zone_id' => $id,
                        'category_id' => $category_id,
                        'position' => $key + 1
                    ]);
                }
            }
        }
        return true;
    }
    public function statusChange($data)
    {
        $newUserZones = NewUserZone::where('id', '!=', $data['id'])->get();
        foreach ($newUserZones as $key => $deal) {
            $deal->update([
                'status' => 0
            ]);
        }
        HeaderNewUserZonePanel::first()->update([
            'new_user_zone_id' => $data['id'],
        ]);
        if ($data['status'] == 0) {
            HeaderNewUserZonePanel::first()->update([
                'new_user_zone_id' => 0,
            ]);
        }
        return NewUserZone::findOrFail($data['id'])->update([
            'status' => $data['status']
        ]);
    }
    public function featuredChange($data)
    {
        return NewUserZone::findOrFail($data['id'])->update([
            'is_featured' => $data['is_featured']
        ]);
    }
    public function editById($id)
    {
        return NewUserZone::findOrFail($id);
    }
    public function deleteById($id)
    {
        $new_user_zone =  NewUserZone::findOrFail($id);
        $products = $new_user_zone->products->pluck('id');
        NewUserZoneProduct::destroy($products);
        $categories = $new_user_zone->categories->pluck('id');
        NewUserZoneCategory::destroy($categories);
        $coupon = NewUserZoneCoupon::where('new_user_zone_id', $id)->first();
        if ($coupon) {
            $coupon->delete();
        }
        UsedMedia::where('usable_id', $new_user_zone->id)->where('usable_type', get_class($new_user_zone))->where('used_for', 'banner_image')->delete();
        $new_user_zone->delete();
        return true;
    }
    public function getSellerProduct()
    {
        $user = auth()->user();
        if ($user->role->type == 'superadmin' || $user->role->type == 'admin' || $user->role->type == 'staff') {
            return SellerProduct::with('product','seller.role')->activeSeller()->get();
        } elseif ($user->role->type == 'seller') {
            return SellerProduct::with('product','seller.role')->where('user_id', $user->id)->activeSeller()->get();
        } else {
            return [];
        }
    }
    public function getCategories()
    {
        return Category::all();
    }
    public function getAllCategory()
    {
        return Category::with('categoryImage', 'parentCategory', 'subCategories')->where('status', 1)->latest()->get();
    }
    public function getCoupons()
    {
        return Coupon::latest()->get();
    }
    public function getActiveNewUserZone()
    {
        $new_user_zone = NewUserZone::with('categories', 'categories.category', 'coupon.coupon', 'couponCategories', 'couponCategories.category')->where('status', 1)->first();

        $data['new_user_zone'] =  new NewUserZoneResource($new_user_zone);

        $new_user_repo = new RepositoriesNewUserZoneRepository();
        $data['allCategoryProducts'] = $new_user_repo->getAllProductsForCategories($data['new_user_zone']->slug);

        $data['allCouponCategoryProducts'] = $new_user_repo->getAllProductsForCouponCategories($data['new_user_zone']->slug);
        return $data;
    }
}
