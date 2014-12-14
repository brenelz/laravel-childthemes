## Laravel Child Themes

Create multiple themes in Laravel and only overwrite the default when you need to instead of copying entire theme.

For example you could have the following directory structure.

- views/DefaultTheme/
  - master.blade.php
  - index.blade.php
  - contact.blade.php
- views/HolidayTheme/
  - master.blade.php

In this case if you have the DefaultTheme as the default, and the HolidayTheme as the active - 
it will render the HolidayTheme master page but keep the index and contact views untouched from the DefaultTheme.

**Please note that child themes are not limited to blade.  They are bound to the Laravel ViewFactory so Twig and other template engines will work as well.**

#### ThemeHelper Facade

Recently I have added a ThemeHelper facade and changed up the config file.  In some cases you don't want to copy the entire blade file but instead just want to swap out strings based on which theme is active.

To use this new feature just put something like the following in your view files.

    <h1>{{ThemeHelper::get('siteName')}}</h1>

It will then output whatever site name you have defined in your config for the active theme.

#### Installation

Add the following to the require section of your `composer.json` file and run an update:

    "brenelz/laravel-childthemes": "dev-master",
    
The final step is adding the service provider to your Laravel application's app.php:

    'Brenelz\Childthemes\ChildthemesServiceProvider',
