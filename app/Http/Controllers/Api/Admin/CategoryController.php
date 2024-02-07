<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category as ModelsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);

        if ($validators->passes()) {
            try {
                // save to db
                $category = new ModelsCategory();
                $category->name = $request->name;
                $category->slug = $request->slug;

                $category->save();

                return response([
                    'status' => true,
                    'message' => 'Category added successflly'
                ]);
            } catch (\Throwable $error) {
                //throw $th;
                return response([
                    'status' => false,
                    'message' => 'Error adding category',
                    'error' => $error
                ]);
            }
        } else {
            return response([
                'status' => false,
                'errors' => $validators->errors()
            ]);
        }
    }

    public function list(Request $request)
    {
        try {
            $categories = ModelsCategory::latest();

            if (!empty($request->get('keyword'))) {
                $categories = $categories->where('name', 'like', '%' . $request->get("keyword") . '%');
            }

            if (!empty($request->pageSize)) {
                $categories = $categories->paginate($request->pageSize);
            } else {
                $categories = $categories->paginate(10);
            }



            return response([
                'status' => true,
                'data' => $categories
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

    public function update(Request $request)
    {

        $category = ModelsCategory::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id'
        ]);

        if ($validator->passes()) {
            try {
                $category->name = $request->name;
                $category->slug = $request->slug;
                $category->save();

                return response([
                    'status' => true,
                    'message' => 'Category updated successfully'
                ]);
            } catch (\Throwable $th) {
                return response([
                    'status' => false,
                    'message' => 'Error fetching data',
                    'error' => $th
                ]);
            }
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function getAllCategories()
    {
        $categories = ModelsCategory::orderBy('name', 'ASC')->get();

        return response([
            'status' => true,
            'data' => $categories
        ]);
    }
}
