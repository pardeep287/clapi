<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\UnderOverUserStat;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ActionRepeatedException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Games\UnderOverController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnderOverUserTransaction extends Model
{
    protected $fillable = [
        'under_over_user_stat_id',
        'description',
        'choice',
        'results',
        'entry_fee',
        'amount',
        'status'
    ];

    public function enterGame(UnderOverController $controllerInstance, Request $request) {
        $gameStat = (new UnderOverUserStat)->getUserStats($controllerInstance, $request);
        $gameStat = $gameStat['data'];
        $gameStat->chargeFee();
    }
    
    public function initializeGame(UnderOverController $controllerInstance, Request $request)
    {
        $r = [];
        $r["data"] = null;
        
        $gameStat = (new UnderOverUserStat)->getUserStats($controllerInstance, $request);
        $gameStat = $gameStat['data'];
        $gameStat->checkFee($request->bet_amount);
        
        $gameTransaction = new UnderOverUserTransaction;
        $gameTransaction->under_over_user_stats_id = $gameStat->id;
        $gameTransaction->bet_amount = $request->bet_amount;
        $gameTransaction->choice = 0;
        $gameTransaction->won_amount = 0;
        $gameTransaction->results = 0;
        $gameTransaction->status = 0;
        $gameTransaction->description = '';

        try {
            $gameTransaction->saveOrFail();
            $gameStat->chargeFee($request->bet_amount);

            $r["data"] = $gameTransaction;
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return $r;
    }



    public function finishGame(UnderOverController $controllerInstance, Request $request)
    {
        $r = [];
        $r["data"] = null;

        try {
            $controllerInstance->validate($request, [
                'results' => 'required',
                'won_amount' => 'required',
                'gameplay_id' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }
        
        $gameStat = (new UnderOverUserStat)->getUserStats($controllerInstance, $request);
        $gameStat = $gameStat['data'];
        
        try {
            $gameTransaction = $gameStat->transactions()
            ->where('id', $request->gameplay_id)
            ->where('status', 0)
            ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException('Game record not found.');
        }
        
        $gameTransaction->results = $request->results;
        $gameTransaction->won_amount = $request->won_amount;
        $gameTransaction->status = 1;

        try {
            $gameTransaction->saveOrFail();
            $r['data'] = $gameTransaction;
            
            $gameStat->gold = $gameStat->gold + $request->won_amount;
            $gameStat->game_coins = $gameStat->game_coins + $request->won_amount;
            $gameStat->saveOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return $r;
    }


    public function saveChoice(UnderOverController $controllerInstance, Request $request) {
        $r = [];
        $r['success'] = false;

        try {
            $controllerInstance->validate($request, [
                'gameplay_id' => 'required|exists:under_over_user_transactions,id',
                'choice' => 'required|in:1,2,3',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        $gameTransaction = self::find($request->gameplay_id);

        if((int)$gameTransaction->choice !== 0) {
            throw new ActionRepeatedException('You have already set your choice. This cannnot be done again');
        }

        $gameTransaction->choice = $request->choice;
        try {
            $gameTransaction->saveOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        $r['success'] = true;

        return $r;
    }
}
