<?php

namespace spec\PhpSpec\Lumen\Runner\Maintainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Prophet;
use Prophecy\Argument;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;

use PhpSpec\Lumen\Util\Lumen;

class LumenMaintainerSpec extends ObjectBehavior
{
    private $refMethod;

    function let(Lumen $lumen, ExampleNode $example, SpecificationInterface $context)
    {
        $this->beConstructedWith($lumen);

        $p = new Prophet;

        $this->refMethod = $p->prophesize('ReflectionMethod');
        $this->refMethod->invokeArgs(Argument::type('PhpSpec\SpecificationInterface'), Argument::type('array'))->shouldBeCalled();

        $refClass = $p->prophesize('ReflectionClass');

        $refClass->hasMethod('setLumen')->willReturn(true);
        $refClass->hasMethod('setLumen')->shouldBeCalled();

        $refClass->getMethod('setLumen')->willReturn($this->refMethod->reveal());
        $refClass->getMethod('setLumen')->shouldBeCalled();

        $specNode = $p->prophesize('PhpSpec\Loader\Node\SpecificationNode');
        $specNode->getClassReflection()->willReturn($refClass->reveal());

        $example->getSpecification()->willReturn($specNode->reveal());
    }

    function it_is_a_maintainer()
    {
        $this->shouldHaveType('PhpSpec\Runner\Maintainer\MaintainerInterface');
    }

    function it_supports_objects_with_a_setLumen_method(ExampleNode $example)
    {
        $this->supports($example)->shouldBe(true);
    }

    function it_sets_a_lumen_object_on_a_spec(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prepare($example, $context, $matchers, $collaborators);
    }

    function it_doesnt_tear_down_anything(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->teardown($example, $context, $matchers, $collaborators);
    }

    function it_has_a_high_priority()
    {
        $this->getPriority()->shouldBe(1000);
    }
}
