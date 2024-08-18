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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('message.{communityId}.{id}', function ($user, $communityId, $id) {

    $community = \App\Models\Community::find($communityId);
    $isUser = (int)$community->user_id === (int)$id || (int)$community->seller_id === (int)$id;
    return (int)$user->id === (int)$id && $isUser;
});

Broadcast::channel('community.{id}', function ($user, $id) {

    return (int)$user->id === (int)$id ;
});
