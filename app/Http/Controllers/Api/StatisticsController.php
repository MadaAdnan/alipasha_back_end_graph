<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function userCount()
    {
        $counts = \DB::table('users')->selectRaw('
    COUNT(*) as total,
    SUM(CASE WHEN email_verified_at IS NOT NULL THEN 1 ELSE 0 END) as verified_count,
    SUM(CASE WHEN email_verified_at IS NULL THEN 1 ELSE 0 END) as unverified_count'
        )->first();
return $counts;
    }
}
