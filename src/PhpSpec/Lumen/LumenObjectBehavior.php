<?php
namespace PhpSpec\Lumen;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Subject;
use PhpSpec\Lumen\Util\Lumen;

/**
 * This behavior should be the base behavior for all of your regular PhpSpec
 * behaviors within your Lumen application.
 */
class LumenObjectBehavior extends ObjectBehavior implements LumenBehaviorInterface
{
    /**
     * Lumen wrapper.
     *
     * @var \PhpSpec\Lumen\Util\Lumen
     */
    protected $lumen;

    /**
     * App presenter.
     *
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    protected $presenter;

    /**
     * Bind Lumen wrapper to this behavior.
     *
     * @param  \PhpSpec\Lumen\Util\Lumen $lumen Lumen wrapper
     * @return \PhpSpec\Lumen\LumenObjectBehavior This
     */
    public function setLumen(Lumen $lumen)
    {
        $this->lumen = $lumen;

        return $this;
    }

    /**
     * Bind the app Presenter to this behaviour.
     *
     * @param  \PhpSpec\Formatter\Presenter\PresenterInterface $presenter
     * @return \PhpSpec\Lumen\LumenObjectBehavior This
     */
    public function setPresenter(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;

        return $this;
    }
}
