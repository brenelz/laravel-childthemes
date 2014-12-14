<?php namespace Brenelz\Childthemes;

use Illuminate\Support\Facades\Facade;

class ThemeHelperFacade extends Facade {
    protected static function getFacadeAccessor() { 
        return 'Brenelz\Childthemes\ThemeHelper'; 
    }
}