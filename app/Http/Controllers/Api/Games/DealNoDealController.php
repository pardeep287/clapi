<?php

namespace App\Http\Controllers\Api\Games;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DealNoDealUserStat;
use App\Models\DealNoDealUserTransaction;

class DealNoDealController extends Controller {
    public function getUserStats(Request $request)
    {
        return (new DealNoDealUserStat)->getUserStats($this, $request);
    }

    public function enterGame(Request $request)
    {
        return (new DealNoDealUserTransaction)->enterGame($this, $request);
    }

    public function playGame(Request $request)
    {
        return (new DealNoDealUserTransaction)->playGame($this, $request);
    }

    public function finishGame(Request $request)
    {
        return (new DealNoDealUserTransaction)->finishGame($this, $request);
    }
}
