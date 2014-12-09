<?php namespace Brenelz\Childthemes\ThemeSwitcher;

class ByHost implements ThemeSwitcher {

	/**
     * @param array $themes
     * @return string
     */
    public function getActiveTheme(array $themes) {
        $host = str_replace('www.','',$_SERVER['HTTP_HOST']);

        foreach( $themes as $theme=>$domains) {
            if(in_array($host,$domains)) return $theme;
            if(in_array('www.'.$host,$domains)) return $theme;
        }
    }
}
