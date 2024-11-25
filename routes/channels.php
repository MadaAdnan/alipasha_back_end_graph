<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
/*Broadcast::channel('change-setting', function ($user) {
    return true;
});*/


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('message.{id}', function (\App\Models\User $user, $id) {

    return in_array($id,$user->communities->pluck('id')->toArray());

});

Broadcast::channel('community.{id}', function ($user, $id) {

    return (int)$user->id === (int)$id ;
});
