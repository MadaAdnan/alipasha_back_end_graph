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
Broadcast::channel('change-setting', function ($user) {
    return true;
});


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('message.{id}', function ($user, $id) {
    $community = \App\Models\Community::find($id);
    $isUser=  $community->users()->where('users.id',$user->id)->exists();
    return $isUser;
});

Broadcast::channel('community.{id}', function ($user, $id) {

    return (int)$user->id === (int)$id ;
});
