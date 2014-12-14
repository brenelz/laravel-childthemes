<?php namespace Brenelz\Childthemes;

use Illuminate\Support\ServiceProvider;
use Brenelz\Childthemes\ThemeSwitcher\Base as ThemeSwitcherBase;
use Illuminate\Foundation\AliasLoader;

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

		$loader = AliasLoader::getInstance();
        $loader->alias('ThemeHelper', 'Brenelz\Childthemes\ThemeHelperFacade');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->package('brenelz/childthemes');
		
		$themeSwitcher = $this->bindThemeSwitcher();
        $activeTheme   = $this->getActiveTheme($themeSwitcher);

        $this->bindThemeHelper($activeTheme);
        $this->bindThemeViewFactory($activeTheme);
	}

	/**
	 * @return Brenelz\Childthemes\ThemeSwitcher\ThemeSwitcherBase
	 */
	private function bindThemeSwitcher() {
		$this->app->bind('Brenelz\Childthemes\ThemeSwitcher\ThemeSwitcherBase',function($app) {
			$class = $app['config']->get('childthemes::switchByClass');
			return new $class();
		});

		return $this->app['Brenelz\Childthemes\ThemeSwitcher\ThemeSwitcherBase'];
	}

	/**
	 * @param string	 
	 * @return string
	 */
	private function getActiveTheme($themeSwitcher) {
		$availableThemes = $this->app['config']->get('childthemes::availableThemes');
		$activeTheme = $themeSwitcher->getActiveTheme($availableThemes);

		if(empty($activeTheme)) {
            $activeTheme = $defaultTheme;
        }

		return $activeTheme;
	}

	/**
	 * @param string
	 * @return Brenelz\Childthemes\ThemeHelper
	 */
	private function bindThemeHelper($activeTheme) {
		$themeVariables = $this->app['config']->get("childthemes::availableThemes.$activeTheme.variables");

		$this->app->bind('Brenelz\Childthemes\ThemeHelper',function($app) use($themeVariables) {
			return new ThemeHelper($themeVariables);
		});

		return $this->app['Brenelz\Childthemes\ThemeHelper'];
	}

	 /**
      * @param string $activeTheme
      * @return Brenelz\Childthemes\ThemeViewFactory
      */
    private function bindThemeViewFactory($activeTheme)
    {
    	$fallbackTheme = $this->app['config']->get('childthemes::defaultTheme');

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

        return $this->app['view'];
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'Brenelz\Childthemes\ThemeHelper',
			'Brenelz\Childthemes\ThemeSwitcher\ThemeSwitcherBase',
			'Brenelz\Childthemes\ThemeViewFactory',
		];
	}

}
