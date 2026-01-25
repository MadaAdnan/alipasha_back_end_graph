<?php

namespace App\Observers;

use App\Models\Comment;
use App\Service\SendNotifyHelper;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        try {
            $product=$comment->product;
            if($comment->comment_id==null){

                $user = $product->user;
                $data['title'] = 'تعليق جديد بواسطة ' . $user->name;
                $data['body'] = 'تم التعليق على منتجك  ' . $product->name ?? $product->expert;

            }else{
                $data['title'] = 'تم الرد على تعليقك' ;
                $data['body'] = 'المنتج: ' . $product->name ?? $product->expert;
                $user=$comment->comment?->user;
            }

            $data['url'] = 'https://ali-pasha.com/comments?id=' . $product->id;
            if($user){
                SendNotifyHelper::sendNotify($user, $data);
            }

        } catch (\Exception | \Error $e) {
        }
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
