<?php

namespace App\Models;

use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\PersonalValidationException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Api\Admin\AreasController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Area extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'city_id',
    ];

    public $timestamps = false;


    public function getAreas()
    {

        $fields = [
            'areas.id',
            'areas.name',
            'cities.name as city_name',
        ];

        return $this->leftJoin('cities', 'cities.id', '=', 'areas.city_id')
            ->get($fields);
    }

    public function fetch(AreasController $controllerInstance, Request $request)
    {
        return self::all();
    }

    public function store(AreasController $controllerInstance, Request $request, $id = false)
    {

        $isEdit = false;
        if ($id) $isEdit = true;

        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'city_id' => 'required|regex:/^[0-9]$/'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::find($id);
            if(!$model) {
                throw new ResourceNotFoundException(__('areas.errors.not_found'));
            }
        } else {
            $model = new Area;
        }
        $model->name = $request->name;
        $model->city_id = $request->city_id;

        if(!$model->save()) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $model;
    }

    public function fetchFirst(AreasController $controllerInstance, Request $request, $id)
    {
        $model = self::find($id);
        if(!$model) {
            throw new ResourceNotFoundException(__("areas.errors.not_found"));
        }
        return $model;
    }

    public function remove(AreasController $controllerInstance, $id)
    {
        $r = self::find($id);
        if(!$r){
            throw new ResourceNotFoundException(__("areas.errors.not_found"));
        }
        if($r->delete()){
            return $r;
        }
    }

    public function getAreaCityWise(AreasController $controllerInstance, Request $request, $id)
    {
        return self::where('city_id',$id)->get();
    }
}
