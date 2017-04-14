<?php
namespace App\Http\ViewComposers;

use App\Handlers\PageTitlesHandler;
use App\Models\Lang\Language;
use App\Models\User\Decorators\UserCacheDecorator;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class AllViewsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currentUser', auth()->user());
    }
}