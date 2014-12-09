<?php namespace Brenelz\Childthemes;

use Illuminate\Support\ServiceProvider;
use Brenelz\Childthemes\ThemeSwitcher\Base as ThemeSwitcherBase;

class ChildthemesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('brenelz/childthemes');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->package('brenelz/childthemes');

		$app = $this->app;

		$this->app->bind('Brenelz\Childthemes\ThemeSwitcher\ThemeSwitcherBase',function($app) {
			$class = $app['config']->get('childthemes::switchByClass');
			return new $class();
		});


		$availableThemes = $app['config']->get('childthemes::availableThemes');
		$defaultTheme = $app['config']->get('childthemes::defaultTheme');
        $activeTheme = $app->make('Brenelz\Childthemes\ThemeSwitcher\ThemeSwitcherBase')->getActiveTheme($availableThemes);


        if(empty($activeTheme)) {
            $activeTheme = $defaultTheme;
        }

        $this->setupThemeViewFactory($defaultTheme, $activeTheme);
	}

	 /**
     * @param string $fallbackTheme
     * @param string $activeTheme
     */
    private function setupThemeViewFactory($fallbackTheme, $activeTheme)
    {
        $this->app['view'] = $this->app->share(function ($app) use ($fallbackTheme, $activeTheme) {
            $factory = new ThemeViewFactory(
                $fallbackTheme,
                $app['view.engine.resolver'],
                $app['view.finder'],
                $app['events']
            );

            $factory->setActiveTheme($activeTheme);
            $factory->setContainer($app);
            $factory->share('app', $app);
            return $factory;
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'view'
		];
	}

}
