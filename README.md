Laravel Child Themes
===================

This package allows you to create multiple themes and only overwrite the default when you need to instead of copying entire theme.

For example you could have the following directory structure.

- views/DefaultTheme/
  - master.blade.php
  - index.blade.php
  - contact.blade.php
- views/HolidayTheme/
  - master.blade.php

In this case if you have the DefaultTheme as the default, and the HolidayTheme as the active - 
it will render the brand new master page but keep the index and contact views untouched.

It is as easy as adding this package to your composer.json (brenelz/laravel-childthemes) and adding the following 
Service Provider to your app.php (Brenelz\Childthemes\ChildthemesServiceProvider)
