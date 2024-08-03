<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;
use function PHPUnit\Framework\isEmpty;

class CategoryController extends Controller
{
    function index(): JsonResponse
    {
        $categories = Category::all();
        if($categories->isEmpty()){
            return $this->notFoundResponse($categories, 'Categories not found');
        }

        return $this->successResponse($categories, 'Users retrieved successfully.', 201);
    }


    function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
           'name' => 'required|string|unique:categories',
        ]);

        if($validated->fails()){
            return $this->invalidFieldResponse($validated->errors());
        }

        $category = Category::create($validated->validated());
        return $this->successResponse($category, 'Category created successfully.', 201);

    }


}
