<?php

namespace spec\PhpSpec\Lumen;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Lumen\Util\Lumen;

class LumenObjectBehaviorSpec extends ObjectBehavior
{
    function it_is_a_lumen_behaviour()
    {
        $this->shouldImplement('PhpSpec\Lumen\LumenBehaviorInterface');
        $this->shouldBeAnInstanceOf('PhpSpec\ObjectBehavior');
    }

    function it_accepts_a_lumen_utility(Lumen $lumen)
    {
        $this->setLumen($lumen)->shouldHaveType('PhpSpec\Lumen\LumenObjectBehavior');
    }

    function it_accepts_a_presenter(PresenterInterface $presenter)
    {
        $this->setPresenter($presenter)->shouldHaveType('PhpSpec\Lumen\LumenObjectBehavior');
    }
}
