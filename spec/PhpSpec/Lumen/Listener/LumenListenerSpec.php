<?php

namespace spec\PhpSpec\Lumen\Listener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Lumen\Util\Lumen;
use PhpSpec\Event\SpecificationEvent;
use PhpSpec\Loader\Node\SpecificationNode;
use ReflectionClass;

class LumenListenerSpec extends ObjectBehavior
{
    function let(Lumen $lumen)
    {
        $this->beConstructedWith($lumen);
    }

    function it_is_a_listener()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_refreshes_the_lumen_framework_before_spec_is_run(Lumen $lumen,
                                                                   SpecificationEvent $event,
                                                                   SpecificationNode $spec,
                                                                   ReflectionClass $refl)
    {
        $event
            ->getSpecification()
            ->shouldBeCalled()
            ->willReturn($spec);

        $spec
            ->getClassReflection()
            ->shouldBeCalled()
            ->willReturn($refl);

        $refl
            ->hasMethod('setLumen')
            ->shouldBeCalled()
            ->willReturn(true);

        $lumen->refreshApplication()->shouldBeCalled();

        $this->beforeSpecification($event);
    }
}
