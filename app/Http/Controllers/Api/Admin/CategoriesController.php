<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Resources\Api\Admin\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{

    public function index(Request $request)
    {
        $result = (new Category)->fetch($this, $request);
        return CategoriesResource::collection($result);
    }

    public function store(Request $request)
    {
        $result = (new Category)->add($this, $request);
        return new CategoriesResource($result);
    }

    public function show(Request $request, $id)
    {
        $result = (new Category)->fetchFirst($this, $request, $id);
        return new CategoriesResource($result);
    }

    public function update(Request $request, $id)
    {
        $result = (new Category)->add($this, $request, $id);
        return new CategoriesResource($result);
    }

    public function destroy($id)
    {
        $result = (new Category)->remove($this, $id);
        return new CategoriesResource($result);
    }

    /*public function getAllCategory(Request $request)
    {
        $result = (new Category)->getAllCategory($this, $request);
        return CategoriesResource::collection($result);
    }*/
    public function getSubCategories(Request $request, $id)
    {
        $request->noParent=true;
        $result = (new Category)->getSubCategoryies($this, $request,$id);
        return CategoriesResource::collection($result);
    }
}
