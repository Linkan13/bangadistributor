<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;

use App\Repositories\MediaManagerRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class MediaManagerController extends Controller
{
    protected $mediaManagerRepo;

    public function __construct(MediaManagerRepository $mediaManagerRepo)
    {
        $this->mediaManagerRepo = $mediaManagerRepo;
    }

    public function index(Request $request){
        $files = $this->mediaManagerRepo->getFiles($request);
        return view('backEnd.media_manager.index', compact('files'));
    }
    public function add_new(){

        return view('backEnd.media_manager.add_new');
    }
    public function store(Request $request){

        $request->validate([
            'file' => ['required']
        ]);

        return $this->mediaManagerRepo->store($request);
    }
    public function getfilesForModal(Request $request){
        $files = $this->mediaManagerRepo->getFiles($request);
        return response()->json([
            'files' => $files
        ]);
    }
    public function destroy($id){
        $result = $this->mediaManagerRepo->destroy($id);
        if($result === true){
            Toastr::success(__('common.deleted_successfully'), __('common.success'));
            return redirect()->back();
        }else{
            Toastr::error(__('common.error_message'), __('common.error'));
            return redirect()->back();
        }
    }

    public function getModal(Request $request){
        return view('backEnd.media_manager.media_modal');
    }

    public function getMediaById(Request $request){
        return $this->mediaManagerRepo->getMediaById($request);
    }

    public function mediaBulkDelete(Request $request)
    {
        $result = $this->mediaManagerRepo->mediaBulkDelete($request->except('_token'));
        if($result === true){
            Toastr::success(__('common.deleted_successfully'), __('common.success'));
            return redirect()->back();
        }else{
            Toastr::error(__('common.error_message'), __('common.error'));
            return redirect()->back();
        }
    }


}
