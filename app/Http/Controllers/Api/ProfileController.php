<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        if ($request->has('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->passes()) {

                $user = Auth::user();

                if (!empty($user->photo)) {
                    //delete old image
                    $imageName = $user->photo;
                    File::delete(public_path() . '/images/' . $imageName);
                }

                $image = $request->file('image');

                $name = time() . '.' . $image->getClientOriginalExtension();

                $image->move('images/', $name);

                // $imageUrl = url('images/' . $name);



                $user = User::find($user->id);
                $user->photo = $name;
                if ($request->has('name')) {
                    $user->name = $request->name;
                }
                $user->save();



                return response([
                    'status' => true,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'role' => $user->usertype
                ]);
            } else {
                return response([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }
    }
}
