<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Admin\HoroscopeCategoriesController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HoroscopeCategory extends Model
{
    protected $fillable = [
        'name', 'from_date','to_date'
    ];
    public $timestamps = false;

    public function fetch(HoroscopeCategoriesController $controllerInstance, Request $request) 
    {
        $r = [];
        $r["data"] = self::all();
        return $r;
    }

    public function add(HoroscopeCategoriesController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        $r = [];
        $r["data"] = null;

        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'from_date' => 'required',
                'to_date' => 'required'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::findOrFail($id);
        } else {
            $model = new HoroscopeCategory;
        }
        $model->name = $request->name;
        $model->from_date = $request->from_date;
        $model->to_date = $request->to_date;
  
        try {
            $model->saveOrFail();
            $r["data"] = $model->toArray();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('countries.errors.invalid_query'));
        }

        return $r;
    }

    public function fetchFirst(HoroscopeCategoriesController $controllerInstance, Request $request, $id)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::findOrFail($id);
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscope-categories.errors.not_found"));
        }
        
        return $r;
    }


    public function remove(HoroscopeCategoriesController $controllerInstance, $id)
    {
        $r = [];
        $r["data"] = [];

        try {
            $r['data'] = self::findOrFail($id);
            $r['data']->delete();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscope-categories.errors.not_found"));
        }
        
        return $r;
    }
}
