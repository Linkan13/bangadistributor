<?php

namespace Modules\Product\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Product\Transformers\TagResource;
use Modules\Product\Repositories\TagRepository;
use Modules\Marketing\Transformers\NewUserZoneCategoryProducts;
/**
* @group Tags
*
* APIs for Tags
*/
class TagController extends Controller
{
    protected $tagRepository;
    public function __construct(TagRepository $tagRepository){
        $this->tagRepository = $tagRepository;
    }

    /**
     * Product tags
     * @response{
     *    "tags": {
     *       "current_page": 1,
     *       "data": [
     *           {
     *               "id": 10,
     *               "tag": "walton",
     *               "created_at": "2021-08-10T12:46:01.000000Z",
     *               "updated_at": "2021-08-10T12:46:01.000000Z",
     *               "Products": {
     *                   "current_page": 1,
     *                   "data": [
     *                       info....
     *                   ],
     *                   "first_page_url": "http://ecommerce.test/api/product/tag?page=1",
     *                   "from": 1,
     *                   "last_page": 1,
     *                   "last_page_url": "http://ecommerce.test/api/product/tag?page=1",
     *                   "links": [
     *                       {
     *                           "url": null,
     *                           "label": "&laquo; Previous",
     *                           "active": false
     *                       },
     *                       {
     *                           "url": "http://ecommerce.test/api/product/tag?page=1",
     *                           "label": "1",
     *                           "active": true
     *                       },
     *                       {
     *                           "url": null,
     *                           "label": "Next &raquo;",
     *                           "active": false
     *                       }
     *                   ],
     *                   "next_page_url": null,
     *                   "path": "http://ecommerce.test/api/product/tag",
     *                   "per_page": 10,
     *                   "prev_page_url": null,
     *                   "to": 1,
     *                   "total": 1
     *               }
     *           },
     *
     *       ],
     *       "first_page_url": "http://ecommerce.test/api/product/tag?page=1",
     *       "from": 1,
     *       "last_page": 2,
     *       "last_page_url": "http://ecommerce.test/api/product/tag?page=2",
     *       "links": [
     *           {
     *               "url": null,
     *               "label": "&laquo; Previous",
     *               "active": false
     *           },
     *           {
     *               "url": "http://ecommerce.test/api/product/tag?page=1",
     *               "label": "1",
     *               "active": true
     *           },
     *           {
     *               "url": "http://ecommerce.test/api/product/tag?page=2",
     *               "label": "2",
     *               "active": false
     *           },
     *           {
     *               "url": "http://ecommerce.test/api/product/tag?page=2",
     *               "label": "Next &raquo;",
     *               "active": false
     *           }
     *       ],
     *       "next_page_url": "http://ecommerce.test/api/product/tag?page=2",
     *       "path": "http://ecommerce.test/api/product/tag",
     *       "per_page": 10,
     *       "prev_page_url": null,
     *       "to": 10,
     *       "total": 13
     *   },
     *   "message": "success"
     *
     * }
     */
    public function index(){

        $tags = $this->tagRepository->tagList();
        if(count($tags) > 0){
            return TagResource::collection($tags);
        }else{
            return response()->json([
                'message' => trans('app.Empty list')
            ], 404);
        }
    }


    /**
     * Single Tag
     *
     * @response{
     *      "tags": {
     *       "id": 1,
     *       "tag": "ktm",
     *       "created_at": "2021-08-08T04:18:53.000000Z",
     *       "updated_at": "2021-08-08T04:18:53.000000Z",
     *       "Products": {
     *           "current_page": 1,
     *           "data": [
     *               product info....
     *           ],
     *           "first_page_url": "http://ecommerce.test/api/product/tag/ktm?page=1",
     *           "from": 1,
     *           "last_page": 1,
     *           "last_page_url": "http://ecommerce.test/api/product/tag/ktm?page=1",
     *           "links": [
     *               {
     *                   "url": null,
     *                   "label": "&laquo; Previous",
     *                   "active": false
     *               },
     *               {
     *                   "url": "http://ecommerce.test/api/product/tag/ktm?page=1",
     *                   "label": "1",
     *                   "active": true
     *               },
     *               {
     *                   "url": null,
     *                   "label": "Next &raquo;",
     *                   "active": false
     *               }
     *           ],
     *           "next_page_url": null,
     *           "path": "http://ecommerce.test/api/product/tag/ktm",
     *           "per_page": 10,
     *           "prev_page_url": null,
     *           "to": 1,
     *           "total": 1
     *       }
     *   },
     *   "message": "success"
     *
     * }
     */

    public function show($tag){
        $data = $this->tagRepository->getByTag($tag);

        if($data){
            $tag = new TagResource($tag);


            return response()->json([
                'tag' => $data['tag'],
                'products' => NewUserZoneCategoryProducts::collection($data['products']),
                'categoryList' => \Modules\Product\Transformers\CategoryResource::collection($data['CategoryList']),
                'brandList' => \Modules\Product\Transformers\BrandResource::collection($data['brandList']),
                'attributeLists' => \Modules\Product\Transformers\AttributeResource::collection($data['attributeLists']),
                'color' => !empty($data['color']) ?  new \Modules\Product\Transformers\ColorResource($data['color']):null,
                'min_price' => $data['min_price'],
                'max_price' => $data['max_price'],
                'message' => trans('app.Success')
            ], 200);
        }else{
            return response()->json([
                'message' => trans('app.Not found')
            ], 404);
        }
    }
}
