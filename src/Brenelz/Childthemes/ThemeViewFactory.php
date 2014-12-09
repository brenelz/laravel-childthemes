<?php namespace Brenelz\Childthemes;

use Illuminate\View\Factory as ViewFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewFinderInterface;

class ThemeViewFactory extends ViewFactory {
    /**
     * @var string
     */
    protected $activeTheme;
    /**
     * @var string
     */
    protected $fallbackTheme;

    /**
     * @param string $fallbackTheme
     * @param EngineResolver $engines
     * @param ViewFinderInterface $finder
     * @param Dispatcher $events
     */
    public function __construct(
        $fallbackTheme,
        EngineResolver $engines,
        ViewFinderInterface $finder,
        Dispatcher $events
    ) {
        $this->setFallbackTheme($fallbackTheme);

        parent::__construct($engines,$finder,$events);
    }

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array())
    {
        $view = $this->stripFallbackFromPath($view);

        $themeView = $this->getActiveTheme().'.'.$view;

        try {
            // check if view exists
            $this->finder->find($themeView);
        } catch( \InvalidArgumentException $e) {
            $themeView = $this->fallbackTheme.'.'.$view;
        }

        return parent::make($themeView,$data,$mergeData);
    }

    /**
     * @return string
     */
    public function getActiveTheme() {
        if(empty($this->activeTheme))
            return $this->fallbackTheme;

        return $this->activeTheme;
    }

    /**
     * @param string $theme
     */
    public function setActiveTheme($theme) {
        $this->activeTheme = $theme;
    }

    /**
     * @param string $theme
     */
    private function setFallbackTheme($theme) {
        $this->fallbackTheme = $theme;
    }

    /**
     * @param string $view
     * @return string
     */
    private function stripFallbackFromPath($view) {
        return str_replace($this->fallbackTheme.".","",$view);
    }
}