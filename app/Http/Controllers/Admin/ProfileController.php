<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name','email','role', 'profile_image']);
    }

    public function update(){
        $validated = request()->validate([
            'name' => ['required'],
            'email' => ['required','email',Rule::unique('users')->ignore(request()->user()->id)]
        ]);

        request()->user()->update($validated);

        return response()->json(['success'=>true]);
    }

    public function updateProfileImage(Request $request){
        if($request->hasFile('profile_image')){
            $previousPath = $request->user()->getRawOriginal('avatar');
            $link = Storage::put('/photos',$request->file('profile_image'));
            Storage::delete($previousPath);
            $request->user()->update([
                'profile_image' => $link
            ]);
            return response()->json(['success'=>true]);
        }
    }
}
