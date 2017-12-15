<?php

namespace App\Models;

use App\Exceptions\PersonalValidationException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Api\Admin\StatesController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Exceptions\PersonalRuntimeException;
use App\Models\Country;
use Illuminate\Validation\ValidationException;

class State extends Model
{
    protected $fillable = [
        'country_id', 'name'
    ];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    
    public function fetch(StatesController $controllerInstance, Request $request) 
    {

        return self::all();
    }

    public function add(StatesController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'country_id' => 'required'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::find($id);
            if(!$model) {
                throw new ResourceNotFoundException(__('states.errors.not_found'));
            }
        } else {
            $model = new State;
        }
        $model->name = $request->name;
        $model->country_id = $request->country_id;

        if(!$model->save()) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $model;
    }

    public function fetchFirst(StatesController $controllerInstance, Request $request, $id)
    {
        $model = self::find($id);
        if(!$model) {
            throw new ResourceNotFoundException(__("states.errors.not_found"));
        }
        return $model;
        

    }

    public function remove(StatesController $controllerInstance, $id)
    {
        $r = self::find($id);
        if(!$r){
            throw new ResourceNotFoundException(__("states.errors.not_found"));
        }
        if($r->delete()){
            return $r;
        }
    }

    public function getStateCountriesWise(StatesController $controllerInstance, Request $request, $id)
    {
        return self::where('country_id',$id)->get();
    }
}
