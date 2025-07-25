<?php

namespace Modules\AoraPageBuilder\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Http;
use Modules\AoraPageBuilder\Http\Requests\CustomPageRequest;
use Modules\AoraPageBuilder\Repositories\PageBuilderRepository;
use App\Traits\ImageStore;
class PageBuilderController extends Controller
{
    use ImageStore;
    protected $pageBuilderRepo;

    public function __construct(PageBuilderRepository $pageBuilderRepository)
    {
        $this->middleware(['auth'])->except('show');
        $this->pageBuilderRepo = $pageBuilderRepository;
        $this->middleware('prohibited_demo_mode')->only(['store','update']);
    }
    public function index()
    {
        try{
            $data['data'] = $this->pageBuilderRepo->all();
            return view('aorapagebuilder::pages.index',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function store(CustomPageRequest $request)
    {
        try{

            $this->pageBuilderRepo->create($request->validated());
            Toastr::success('Operation successful','Success');
            return back();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function design($id)
    {
        try{
            $data['row'] = $this->pageBuilderRepo->find($id);

             return view('aorapagebuilder::pages.design',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function designUpdate(Request $request ,$id){
        try{

            $data =  $this->pageBuilderRepo->designUpdate($request->all(),$id);

            return response()->json(['status'=>200]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function show($id)
    {
        try{
            $data['row'] = $this->pageBuilderRepo->find($id);

            return view('aorapagebuilder::pages.show',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function edit($id)
    {
        try{
            $data['row'] = $this->pageBuilderRepo->find($id);
            return view('aorapagebuilder::pages.edit',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function update(CustomPageRequest $request)
    {
        try{
            $this->pageBuilderRepo->update($request->validated(),$request->id);
            Toastr::success('Operation Successfull','Success');
            return back();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function destroy(Request $request)
    {
        try{
            $this->pageBuilderRepo->delete($request->id);;
            Toastr::success('Operation Successfull','Success');
            return back();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }
    public function status(Request $request){
        try{
            $this->pageBuilderRepo->status($request->except('_token'));
            return true;
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }
    private function reloadWithData(){
        try{
            $data = $this->pageBuilderRepo->all();
            return response()->json([
                'TableData' =>  (string)view('aorapagebuilder::pages.list',['data'=>$data]),
            ],200);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }
    public function snippet()
    {
        try{
            return view('aorapagebuilder::snippet.index');
            // return view('aorapagebuilder::snippet.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
    public function slugGenerate(Request $request)
    {
        try{
            $slug = Str::slug($request->title,'-');
            return response()->json(['slug'=>$slug]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function upload(Request $request)
    {

        try {
            if ($request->hasFile('upload')) {

                $url = $this->saveImage($request->file('upload'));
                if (!empty($url)) {
                    $url = asset(''.$url);
                }
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');

                $msg = trans('common.Image uploaded successfully');
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

                @header('Content-type: text/html; charset=utf-8');
                echo $response;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deleteModal($id)
    {
        $data['row'] = $this->pageBuilderRepo->find($id);
        return view('aorapagebuilder::pages._deleteModalForAjax',$data);
    }
}
