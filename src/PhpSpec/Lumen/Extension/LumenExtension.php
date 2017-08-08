<?php
namespace PhpSpec\Lumen\Extension;

use InvalidArgumentException;
use PhpSpec\Extension;
use PhpSpec\ServiceContainer;
use PhpSpec\Lumen\Listener\LumenListener;
use PhpSpec\Lumen\Runner\Maintainer\LumenMaintainer;
use PhpSpec\Lumen\Runner\Maintainer\PresenterMaintainer;
use PhpSpec\Lumen\Util\Lumen;

/**
 * Setup the Lumen extension.
 *
 * Bootstraps Lumen and sets up some objects in the Container.
 */
class LumenExtension implements Extension
{
    /**
     * Setup the Lumen extension.
     *
     * @param  \PhpSpec\ServiceContainer $container
     * @param array $params
     * @return void
     */
    public function load(ServiceContainer $container, array $params=[])
    {
        // Create & store Lumen wrapper

        $container->define(
            'lumen',
            function ($c) use ($params) {
                $appENV = empty($params['testing_environment']) ? null : $params['testing_environment'];
                $basePath = empty($params['framework_path']) ? null : $params['framework_path'];
                $basePath = $this->getBootstrapPath($basePath);
                $lumen = new Lumen($appENV, $basePath);

                return $lumen;
            }
        );

        // Bootstrap maintainer to bind Lumen wrapper to specs

        $container->define(
            'runner.maintainers.lumen',
            function (ServiceContainer $c) {
                return new LumenMaintainer(
                    $c->get('lumen')
                );
            },
            ['runner.maintainer']
        );

        // Bootstrap maintainer to bind app Presenter to specs, so it
        // can be passed to custom matchers

        $container->define(
            'runner.maintainers.presenter',
            function ($c) {
                return new PresenterMaintainer(
                    $c->get('formatter.presenter')
                );
            },
            ['runner.maintainers']
        );

        // Bootstrap listener to setup Lumen application for specs

        $container->define(
            'event_dispatcher.listeners.lumen',
            function ($c) {
                return new LumenListener($c->get('lumen'));
            },
            ['event_dispatcher.listeners']
        );
    }

    /**
     * Get path to bootstrap file.
     *
     * @param  null|string $path Optional bootstrap file path
     * @return null|string       Bootstrap file path
     */
    private function getBootstrapPath($path = null)
    {
        if (!$path) {
            $relPath = DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'app.php';
            $path = dirname($this->getVendorPath()) . $relPath;
        } elseif (!$this->isAbsolutePath($path)) {
            $path = $this->getVendorPath() . '/' . $path;
        }
        $path = 'D:\git\targeting-new\bootstrap\app.php';

        if (!is_file($path)) {
            throw new InvalidArgumentException("App bootstrap at `{$path}` not found.");
        }

        return $path;
    }

    /**
     * Check if the given path is absolute.
     *
     * @param  $path   Path to check
     * @return boolean True if absolute, false if not
     */
    private function isAbsolutePath($path)
    {
        return ($path !== null) && (strpos($path, '/') === 0);
    }

    /**
     * Get path to vendor/ directory.
     *
     * @return string Absolute path to vendor directory
     */
    private function getVendorPath()
    {
        return realpath(__DIR__ . '/../../../../');
    }
}
