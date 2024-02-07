<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
        ]);

        if ($validator->passes()) {
            // store to db

            try {
                $subCategory = new SubCategory();
                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->category_id = $request->category;
                $subCategory->save();

                return response([
                    'status' => true,
                    'message' => 'Sub Category added successflly'
                ]);
            } catch (\Throwable $error) {
                return response([
                    'status' => false,
                    'message' => 'Error adding category',
                    'error' => $error
                ]);
            }
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function list(Request $request)
    {
        try {
            $subCategories = SubCategory::latest();

            if (!empty($request->get('keyword'))) {
                $subCategories = $subCategories->where('name', 'like', '%' . $request->get("keyword") . '%');
            }

            if (!empty($request->pageSize)) {
                $subCategories = $subCategories->paginate($request->pageSize);
            } else {
                $subCategories = $subCategories->paginate(10);
            }



            return response([
                'status' => true,
                'data' => $subCategories
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

    public function getAllSubCategories()
    {
        $subCategories = SubCategory::orderBy('name', 'ASC')->get();

        return response([
            'status' => true,
            'data' => $subCategories
        ]);
    }
}
