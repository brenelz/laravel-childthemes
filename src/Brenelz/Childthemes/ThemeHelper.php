<?php namespace Brenelz\Childthemes;

use Illuminate\Config\Repository as Config;

class ThemeHelper {
   protected $config;

   public function __construct(array $config) {
        $this->config = $config;
   }

   public function get($key) {
        return $this->config[$key];
   }
}