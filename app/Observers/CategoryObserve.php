<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserve
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
       /* $categoriesId = [];
        $main = $category->category;
        for (; $main != null;) {
            $categoriesId[] = $main->id;
            $main = $main->category;
        }
        $category->categories()->sync($categoriesId);*/

    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
       /* $categoriesId = [];
        $main = $category->category;
        for (; $main != null;) {
            $categoriesId[] = $main->id;
            $main = $main->category;
        }
        $category->categories()->sync($categoriesId);*/
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
