<?php
namespace PhpSpec\Lumen\Extension;

use InvalidArgumentException;
use PhpSpec\ServiceContainer;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Lumen\Listener\LumenListener;
use PhpSpec\Lumen\Runner\Maintainer\LumenMaintainer;
use PhpSpec\Lumen\Runner\Maintainer\PresenterMaintainer;
use PhpSpec\Lumen\Util\Lumen;

/**
 * Setup the Lumen extension.
 *
 * Bootstraps Lumen and sets up some objects in the Container.
 */
class LumenExtension implements ExtensionInterface
{
    /**
     * Setup the Lumen extension.
     *
     * @param  \PhpSpec\ServiceContainer $container
     * @return void
     */
    public function load(ServiceContainer $container)
    {
        // Create & store Lumen wrapper

        $container->setShared(
            'lumen',
            function ($c) {
                $config = $c->getParam('lumen_extension');

                $lumen = new Lumen(
                    isset($config['testing_environment']) ? $config['testing_environment'] : null,
                    $this->getBootstrapPath(
                        isset($config['framework_path']) ? $config['framework_path'] : null
                    )
                );

                return $lumen;
            }
        );

        // Bootstrap maintainer to bind Lumen wrapper to specs

        $container->setShared(
            'runner.maintainers.lumen',
            function ($c) {
                return new LumenMaintainer(
                    $c->get('lumen')
                );
            }
        );

        // Bootstrap maintainer to bind app Presenter to specs, so it
        // can be passed to custom matchers

        $container->setShared(
            'runner.maintainers.presenter',
            function ($c) {
                return new PresenterMaintainer(
                    $c->get('formatter.presenter')
                );
            }
        );

        // Bootstrap listener to setup Lumen application for specs

        $container->setShared(
            'event_dispatcher.listeners.lumen',
            function ($c) {
                return new LumenListener($c->get('lumen'));
            }
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
            $path = dirname($this->getVendorPath()) . '/bootstrap/app.php';
        } elseif (!$this->isAbsolutePath($path)) {
            $path = $this->getVendorPath() . '/' . $path;
        }

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
        return realpath(__DIR__ . '/../../../../../..');
    }
}
