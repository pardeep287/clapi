<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Exceptions\InsufficientLifesException;
use App\Http\Controllers\Api\Games\DealNoDealController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Api\Games\DealNoDealUserTransactionResource;

class DealNoDealUserTransaction extends Model
{
    protected $fillable = [
        'description',
        'entrance_fee',
        'amount',
        'status',
        'win_status'
    ];


    public function enterGame(DealNoDealController $controllerInstance, Request $request) {
        $gameStat = (new DealNoDealUserStat)
            ->getUserStats($controllerInstance, $request);
        $gameStat->chargeFee();
    }

    
    public function playGame(DealNoDealController $controllerInstance, Request $request)
    {
        $gameStatInstance = new DealNoDealUserStat;
        $gameStat = $gameStatInstance->getUserStats($controllerInstance, $request);
        $gameStat->checkFee(config('games.dond.entrance_fee.LEVEL1'));
        
        $gameTransaction = new DealNoDealUserTransaction([
            'entrance_fee' => config('games.dond.entrance_fee.LEVEL1'),
            'amount' => 0,
            'status' => 0,
            'description' => ''
        ]);

        try {
            if (!$gameStat->transactions()->save($gameTransaction)) {
                throw new PersonalRuntimeException(__('common.errors.invalid_query'));
            }

            $gameStat->chargeFee(config('games.dond.entrance_fee.LEVEL1'));
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return new DealNoDealUserTransactionResource($gameTransaction);
    }


    public function finishGame(DealNoDealController $controllerInstance, Request $request)
    {
        try {
            $controllerInstance->validate($request, [
                'won_amount' => 'required',
                'gameplay_id' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        $gameStatInstance = new DealNoDealUserStat;
        $gameStat = $gameStatInstance->getUserStats($controllerInstance, $request);
        $gameStat = DealNoDealUserStat::find($gameStat->id);
        
        try {
            $gameTransaction = $gameStat->transactions()
            ->where('id', $request->gameplay_id)
            ->where('status', 0)
            ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException('Game record not found.');
        }

        $gameTransaction->amount = $request->won_amount;
        $gameTransaction->status = 1;

        try {
            $gameTransaction->saveOrFail();

            $gameStat->gold += $request->won_amount;
            $gameStat->game_coins += $request->won_amount;
            $gameStat->saveOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return new DealNoDealUserTransactionResource($gameTransaction);
    }
}
