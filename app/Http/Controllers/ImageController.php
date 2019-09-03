<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function listImages(){
        $user = Hotel::find(Auth::id());
        return view('settings.updatePhotos')->with(['images'=>$user->images]);
    }

    public function store(Request $request){
        $data = $request->all();
        $validateRequest = Validator::make($data,[
            'uploadedImage'=>['required','mimes:jpeg,png,bmp,tiff','max:8192','min:124'],
            'addType' => ['required','in:main,extra'],
        ]);

        if ($validateRequest->fails()){
            return back()->withErrors($validateRequest->errors());
        }
        $image = new Image();
        $image->link = Storage::disk('public')->put(Auth::user()->username,$request->file('uploadedImage'));
        $image->type = $request->input('addType');
        $image->desc = $request->input('addDescription');
        $image->hotel_id = Auth::id();
        if($image->save())
            return back()->with(['added'=>true]);
        return back()->with(['added'=>false]);
    }

    public function update(Request $request){
        $data = $request->all();
        $validateRequest = Validator::make($data,[
            'modifyType' => ['required','in:main,extra'],
        ]);

        if ($validateRequest->fails()){
            return response()->json('fails');
        }

        $imageID = $request->input('imageID');
        $image = Image::find($imageID);
        $image -> type = $request->input('modifyType');
        $image -> desc = $request->input('modifyDescription');
        if ($image -> update())
            return response()->json('modified');
        return response()->json('failed');
    }

    public function delete(Request $request){
        $imageID = $request->input('imageID');
        $image = Image::find($imageID);
        if($image->delete() && Storage::disk('public')->delete($image->link))
            return response()->json('deleted');
        return response()->json('failed');
    }

}