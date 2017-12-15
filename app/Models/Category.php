<?php

namespace App\Models;

use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\PersonalValidationException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Api\admin\CategoriesController;
use http\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Category extends Model
{
    protected $fillable = [
        'name', 'parent_id'
    ];
    protected $urlFields = [
        'id',
        'name',
        'parent_id',
    ];
    public $timestamps = false;

    public function parent() {
        return $this->belongsTo('App\Models\Category', 'parent_id', 'id');
    }

    public function fetch(CategoriesController $controllerInstance, Request $request)
    {
        // parse columns list if specified in url
        if ($request instanceof Request) {
            $fields = ($request->fields) ? explode(',', $request->fields) : null;
            if($fields) {
                $fields = array_map('trim', $fields);

                // validate if the requested fields are allowed to be fetched
                $validatedFields = [];
                foreach($fields as $f) {
                    if (in_array($f, $this->urlFields)) {
                        $validatedFields[] = $f;
                    }
                }
            }
        }
        $model = null;
        try {
            $model = self::with(['parent']);
            $model->select($this->urlFields);
            if(count($fields) > 0) {
                $model->select($validatedFields);
            }
            $model = $model ->get();

        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }
        return $model;
    }

    public function add(CategoriesController $controllerInstance, Request $request, $id = false)
    {

        $isEdit = false;
        if ($id) $isEdit = true;

        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::find($id);

            if(!$model) {
                throw new ResourceNotFoundException(__('categories.errors.not_found'));
            }
        } else {
            $model = new Category;
        }
        $model->name = $request->name;
        $model->parent_id = ($request->parent_id)?$request->parent_id:null;

        if(!$model->save()) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $model;
    }

    public function fetchFirst(CategoriesController $controllerInstance, Request $request, $id)
    {
        $model = self::find($id);
        if(!$model) {
            throw new ResourceNotFoundException(__("categories.errors.not_found"));
        }
        return $model;
    }

    public function remove(CategoriesController $controllerInstance, $id)
    {
        $r = self::find($id);
        if(!$r){
            throw new ResourceNotFoundException(__("categories.errors.not_found"));
        }
        if($r->delete()){
            return $r;
        }
    }

    public function getAllCategory(CategoriesController $controllerInstance, Request $request)
    {
        return self::where('parent_id',null)->get();
    }

    public function getSubCategoryies(CategoriesController $controllerInstance, Request $request, $id)
    {
        return self::where('parent_id',$id)->get();
    }




}
