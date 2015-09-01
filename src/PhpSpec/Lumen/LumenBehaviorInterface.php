<?php
namespace PhpSpec\Lumen;

use PhpSpec\Lumen\Util\Lumen;
use PhpSpec\Formatter\Presenter\PresenterInterface;

/**
 * Behaviours that implements this interface should provide a public method
 * with which to bind the Lumen wrapper instance.
 */
interface LumenBehaviorInterface
{
    /**
     * Bind Lumen wrapper to the implementing object.
     *
     * @param Lumen $lumen
     */
    public function setLumen(Lumen $lumen);

    /**
     * Bind the app Presenter to the implementing object.
     *
     * @param \PhpSpec\Formatter\Presenter\PresenterInterface $presenter
     */
    public function setPresenter(PresenterInterface $presenter);
}
