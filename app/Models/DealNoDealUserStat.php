<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\InsufficientGoldException;
use App\Exceptions\InsufficientLifesException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Games\DealNoDealController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\DealNoDealUserTransaction;
use App\Http\Resources\Api\Games\DealNoDealUserStatResource;

class DealNoDealUserStat extends Model
{
    protected $fillable = [
        'user_id',
        'game_coins',
        'gold',
        'lifes',
        'purchased_lifes'
    ];

    protected $statsFields = [
        'id',
        'game_coins',
        'gold',
        'lifes',
        'purchased_lifes'
    ];


    public function transactions() {
        return $this->hasMany(DealNoDealUserTransaction::class, 'deal_no_deal_user_stats_id', 'id');
    }
    

    /**
     * Finds the game stats for deal no deal of logged-in user
     * 
     * @param DealNoDealController $controllerInstance
     * @return DealNoDealUserStatResource
     */
    public function getUserStats(DealNoDealController $controllerInstance, $request)
    {
        $userId = $request->_user_->id;
        $fields = [];
        
        // parse columns list if specified in url
        if ($request instanceof Request) {
            $fields = ($request->fields) ? explode(',', $request->fields) : null;
            if($fields) {
                $fields = array_map('trim', $fields);
    
                // validate if the requested fields are allowed to be fetched
                $validatedFields = [];
                foreach($fields as $f) {
                    if (in_array($f, $this->statsFields)) {
                        $validatedFields[] = $f;
                    }
                }
            }
        }

        $userStat = null;
        try {
            $userStat = self::where('user_id', $userId);

            $userStat->select($this->statsFields);
            if(count($fields) > 0) {
                $userStat->select($validatedFields);
            }

            $userStat = $userStat->firstOrFail();
        } catch(QueryException $ex) {
        } catch(ModelNotFoundException $ex) {
            $userStat = new DealNoDealUserStat;
            $userStat->user_id = $userId;
            $userStat->game_coins = 1000;
            $userStat->gold = 2000;
            $userStat->lifes = 3;
            $userStat->purchased_lifes = 0;

            try {
                $userStat->saveOrFail();
            } catch(QueryException $ex) {
                throw new PersonalRuntimeException($ex->getMessage());
            }
        }

        return new DealNoDealUserStatResource($userStat);
    }


    public function checkFee($amount = false) {
        if(!$amount) {
            $fee = config('games.dond.entry_fee');
        } else {
            $fee = $amount;
        }
        
        if ($this->lifes <= 0) {
            // throw new InsufficientLifesException(__('games.errors.insufficient_lifes'));
        } elseif ($this->gold < $fee) {
            throw new InsufficientGoldException(__('games.errors.insufficient_gold', ['fee' => $fee]));
        }
    }


    public function chargeFee($amount = false) {
        // charge user for gameplay
        $this->checkFee($amount);

        if(!$amount) {
            $fee = config('games.dond.entry_fee');
        } else {
            $fee = $amount;
        }
        
        // deduct from the gold of current user
        $this->gold = $this->gold - $fee;
        // $this->lifes = $this->lifes - 1;
        try {
            $this->saveOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }
    }
}
