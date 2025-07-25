<?php

namespace Modules\GiftCard\Imports;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\GiftCard\Entities\GiftCard;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GiftCardImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new GiftCard([
            'name' => $row['name'],
            'sku' => $row['sku'],
            'selling_price' => $row['selling_price'],
            'discount' => $row['discount'],
            'discount_type' => $row['discount_type'],
            'start_date' => Carbon::now()->format('y-m-d'),
            'end_date' => Carbon::now()->format('y-m-d'),
            'status' => 1,
            'description' => $row['description'],
            'shipping_id' => 1,
            "in_app_purchase" => $row['in_app_purchase']
        ]);
    }
}
