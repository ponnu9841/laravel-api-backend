<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getUsers(Request $request)
    {
        try {
            $users = User::latest()->where('usertype', 'user');

            if (!empty($request->get('keyword'))) {
                $users = $users->where('name', 'like', '%' . $request->get("keyword") . '%');
            }

            if (!empty($request->pageSize)) {
                $users = $users->paginate($request->pageSize);
            } else {
                $users = $users->paginate(10);
            }

            return response([
                'status' => true,
                'data' => $users
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => false,
                'message' => 'Error fetching data',
                'error' => $th
            ]);
        }
    }

    public function attachAttribute(Request $request)
    {
    }

    public function getUserById(Request $request)
    {
        $user = User::find($request->id);
        if (!empty($user)) {

            if ($user->category_id) {
                $user->category_id = explode(',', $user->category_id);
            }
            if ($user->sub_category_id) {
                $user->sub_category_id = explode(',', $user->sub_category_id);
            }

            return response([
                'status' => true,
                'data' => $user
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'User does not exist'
            ]);
        }
    }

    public function updateProfileIds(Request $request)
    {
        $category_id = implode(',', $request->category_id);
        $sub_category_id = implode(',', $request->sub_category_id);

        $id = $request->id;

        $user = User::find($id);
        if (!empty($user)) {
            if (!empty($category_id)) {
                $user->category_id = $category_id;
            }
            if (!empty($sub_category_id)) {
                $user->sub_category_id = $sub_category_id;
            }
            $user->save();

            return response([
                'status' => true,
                'message' => 'Upadated successfully'
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
    }
}
