<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use App\Http\Resources\FlashDealResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AllProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allPdctsVariantDetails = null;
        if (isset($this->variantDetails)) {
            foreach ($this->variantDetails as $productVariantDetail) {
                if(!empty(json_decode($productVariantDetail->name, true)) ){
                    foreach (json_decode($productVariantDetail->name, true) as $name) {
                        $productVariantDetailName = $name;
                    }
                }else{
                    $productVariantDetailName = $productVariantDetail->name;

                }
                if(!empty($productVariantDetailName)){
                   $allPdctsVariantDetails[] = [
                        "value" => $productVariantDetail->value,
                        "code" => $productVariantDetail->code,
                        "attr_val_id" => $productVariantDetail->attr_val_id,
                        "name" => $productVariantDetailName,
                        "attr_id" => $productVariantDetail->attr_id,
                    ];

                }

            }
        }

        $allPdctPdct = null;
        if (isset($this->product)) {
            $pdctsPdct = $this->product;

            $allPdctPdct = [
                "id" => $pdctsPdct->id,
                "product_name" => $pdctsPdct->product_name,
                "product_type" => $pdctsPdct->product_type,
                "variant_sku_prefix" => $pdctsPdct->variant_sku_prefix,
                "unit_type_id" => $pdctsPdct->unit_type_id,
                "brand_id" => $pdctsPdct->brand_id,
                "thumbnail_image_source" => $pdctsPdct->thumbnail_image_source,
                "media_ids" => $pdctsPdct->media_ids,
                "barcode_type" => $pdctsPdct->barcode_type,
                "model_number" => $pdctsPdct->model_number,
                "shipping_type" => $pdctsPdct->shipping_type,
                "shipping_cost" => $pdctsPdct->shipping_cost,
                "discount_type" => $pdctsPdct->discount_type,
                "discount" => $pdctsPdct->discount,
                "tax_type" => $pdctsPdct->tax_type,
                "gst_group_id" => $pdctsPdct->gst_group_id,
                "tax_id" => $pdctsPdct->tax_id,
                "tax" => $pdctsPdct->tax,
                "pdf" => $pdctsPdct->pdf,
                "video_provider" => $pdctsPdct->video_provider,
                "video_link" => $pdctsPdct->video_link,
                "description" => $pdctsPdct->description,
                "specification" => $pdctsPdct->specification,
                "minimum_order_qty" => $pdctsPdct->minimum_order_qty,
                "max_order_qty" => $pdctsPdct->max_order_qty,
                "meta_title" => $pdctsPdct->meta_title,
                "meta_description" => $pdctsPdct->meta_description,
                "meta_image" => $pdctsPdct->meta_image,
                "is_physical" => $pdctsPdct->is_physical,
                "is_approved" => $pdctsPdct->is_approved,
                "status" => $pdctsPdct->status,
                "display_in_details" => $pdctsPdct->display_in_details,
                "requested_by" => $pdctsPdct->requested_by,
                "created_by" => $pdctsPdct->created_by,
                "slug" => $pdctsPdct->slug,
                "stock_manage" => $pdctsPdct->stock_manage,
                "subtitle_1" => $pdctsPdct->subtitle_1,
                "subtitle_2" => $pdctsPdct->subtitle_2,
                "updated_by" => $pdctsPdct->updated_by,
                "created_at" => $pdctsPdct->created_at,
                "updated_at" => $pdctsPdct->updated_at,

            ];
        }

        $allPdctSkus = [];
        if (isset($this->skus)) {
            foreach ($this->skus as $allProductsku) {
                $in_purchase = $allProductsku->product->product->skus->where('id',$allProductsku->product_sku_id)->first();
                $purchase_code  = '';
                if(!empty($allProductsku->in_app_purchase))
                {
                    $purchase_code = $allProductsku->in_app_purchase;
                }else{
                    $purchase_code = !empty($in_purchase) ? $in_purchase->in_app_purchase:'';
                }
                $allPdtsSkuPdctVrans = [];
                if (isset($allProductsku->product_variations)) {
                    foreach ($allProductsku->product_variations as $productVariation) {
                        $productVariationAttribute = $productVariation->attribute;
                        if(!empty(json_decode($productVariationAttribute->name, true))){
                            foreach (json_decode($productVariationAttribute->name, true) as $pdctVrntAttrNm) {
                                $pdctVrntAttrName = $pdctVrntAttrNm;
                            }

                        }

                        if(!empty($pdctVrntAttrName)){
                           $allPdtsSkuPdctVrans[] = [
                                "id" => $productVariation->id,
                                "product_id" => $productVariation->product_id,
                                "product_sku_id" => $productVariation->product_sku_id,
                                "attribute_id" => $productVariation->attribute_id,
                                "attribute_value_id" => $productVariation->attribute_value_id,
                                "created_by" => $productVariation->created_by,
                                "updated_by" => $productVariation->updated_by,
                                "created_at" => $productVariation->created_at,
                                "updated_at" => $productVariation->updated_at,
                                "attribute_value" => $productVariation->attribute_value,
                                'in_app_purchase' => $purchase_code,
                                "attribute" => [
                                    "id" => $productVariationAttribute->id,
                                    "name" => $pdctVrntAttrName,
                                    "display_type" => $productVariationAttribute->display_type,
                                    "description" => $productVariationAttribute->description,
                                    "status" => $productVariationAttribute->status,
                                    "created_by" => $productVariationAttribute->created_by,
                                    "updated_by" => $productVariationAttribute->updated_by,
                                    "created_at" => $productVariationAttribute->created_at,
                                    "updated_at" => $productVariationAttribute->updated_at,
                                ],
                            ];
                        }

                    }
                }



                $allPdctSkus[] = [
                    "id" => $allProductsku->id,
                    "user_id" => $allProductsku->user_id,
                    "product_id" => $allProductsku->product_id,
                    "product_sku_id" => $allProductsku->product_sku_id,
                    "product_stock" => $allProductsku->product_stock,
                    "purchase_price" => $allProductsku->purchase_price,
                    "selling_price" => $allProductsku->selling_price,
                    "status" => $allProductsku->status,
                    'in_app_purchase' => $purchase_code,
                    "created_at" => $allProductsku->created_at,
                    "updated_at" => $allProductsku->updated_at,
                    "product_variations" => $allPdtsSkuPdctVrans,
                ];
            }
        }

        $hasFlashDeal = null;
        $deal  = null;
        if(!empty($this->hasDeal))
        {
            $deal = null;

            if(!empty($this->hasDeal->FlashDeal))
            {
                $deal = [
                    "id" => 1,
                    "title" =>$this->hasDeal->FlashDeal->title,
                    "background_color"=> $this->hasDeal->FlashDeal->background_color,
                    "text_color"=> $this->hasDeal->FlashDeal->text_color,
                    "start_date"=> $this->hasDeal->FlashDeal->start_date,
                    "end_date"=> $this->hasDeal->FlashDeal->end_date,
                    "slug"=> $this->hasDeal->FlashDeal->slug,
                    "banner_image"=> $this->hasDeal->FlashDeal->banner_image,
                    "status"=> $this->hasDeal->FlashDeal->status,
                    "is_featured" => $this->hasDeal->FlashDeal->is_featured,
                    "created_by" => $this->hasDeal->FlashDeal->created_by,
                    "updated_by" => $this->hasDeal->FlashDeal->updated_by,
                ];
            }
            $hasFlashDeal = [
                "id" => $this->hasDeal->id,
                "flash_deal_id" => $this->hasDeal->flash_deal_id,
                "seller_product_id" => $this->hasDeal->seller_product_id,
                "discount" => $this->hasDeal->discount,
                "discount_type" => $this->hasDeal->discount_type,
                "status" => $this->hasDeal->status,

                "flash_deal" => $deal
            ];
        }


        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "product_id" => $this->product_id,
            "tax" => $this->tax,
            "tax_type" => $this->tax_type,
            "discount" => $this->discount,
            "discount_type" => $this->discount_type,
            "discount_start_date" => $this->discount_start_date,
            "discount_end_date" => $this->discount_end_date,
            "product_name" => $this->product_name,
            "slug" => $this->slug,
            "thum_img" => $this->thum_img,
            "status" => $this->status,
            "stock_manage" => $this->stock_manage,
            "is_approved" => $this->is_approved,
            "min_sell_price" => $this->min_sell_price,
            "max_sell_price" => $this->max_sell_price,
            "total_sale" => $this->total_sale,
            "avg_rating" => $this->avg_rating,
            "recent_view" => $this->recent_view,
            "subtitle_1" => $this->subtitle_1,
            "subtitle_2" => $this->subtitle_2,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "variantDetails" => $allPdctsVariantDetails,
            "MaxSellingPrice" => $this->MaxSellingPrice,
            "hasDeal" => $hasFlashDeal,
            "rating" => $this->rating,
            "hasDiscount" => $this->hasDiscount,
            "ProductType" => $this->ProductType,
            "flash_deal" => $deal,
            "product" => $allPdctPdct,
            "reviews" => $this->reviews,
            "skus" => $allPdctSkus,
        ];
    }
}
