<?php

namespace App\GraphQL\Resolvers;

final class HelperBalanceResolve
{

    public function getTotalBalance($root)
    {
        return \DB::table('balances')->where('user_id', $root->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()?->total ?? 0;
    }

    public function getTotalPoint($root)
    {
        return \DB::table('points')->where('user_id', $root->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()?->total ?? 0;
    }

}
