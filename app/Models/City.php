<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Admin\CitiesController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class City extends Model
{
    protected $fillable = [
        'name', 'state_id'
    ];
    public $timestamps = false;

    
    public function fetch(CitiesController $controllerInstance, Request $request) 
    {
        return self::all();
    }


    public function add(CitiesController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;


        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'state_id' => 'required|regex:/^[0-9]$/'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::find($id);
            if(!$model) {
                throw new ResourceNotFoundException(__('cities.errors.not_found'));
            }
        } else {
            $model = new City;
        }
        $model->name = $request->name;
        $model->state_id = $request->state_id;

        if(!$model->save()) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $model;
    }

    public function fetchFirst(CitiesController $controllerInstance, Request $request, $id)
    {
        $model = self::find($id);
        if(!$model) {
            throw new ResourceNotFoundException(__("states.errors.not_found"));
        }
        return $model;
    }


    public function remove(CitiesController $controllerInstance, $id)
    {
        $r = self::find($id);
        if(!$r){
            throw new ResourceNotFoundException(__("states.errors.not_found"));
        }
        if($r->delete()){
            return $r;
        }
    }

    public function getCityStateWise(CitiesController $controllerInstance, Request $request, $id)
    {
        return self::where('state_id',$id)->get();
    }
}
