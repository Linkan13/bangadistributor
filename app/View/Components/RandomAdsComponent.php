<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Modules\FrontendCMS\Entities\HomePageSection;

class RandomAdsComponent extends Component
{
    
    public function __construct()
    {
        //
    }

    
    public function render()
    {
        $more_products_get = HomePageSection::where('section_name','more_products')->first();

        $ads = \Modules\FrontendCMS\Entities\SubscribeContent::find(4);
        return view(theme('components.random-ads-component'),compact('ads', 'more_products_get'));
    }
}
