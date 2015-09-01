<?php
namespace PhpSpec\Lumen\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\SpecificationInterface;
use PhpSpec\Lumen\Util\Lumen;

/**
 * This maintainer is used to bind the Lumen wrapper to nodes that implement
 * the `setLumen` method.
 */
class LumenMaintainer implements MaintainerInterface
{
    /**
     * Lumen wrapper.
     *
     * @var \PhpSpec\Lumen\Util\Lumen
     */
    private $lumen;

    /**
     * Constructor.
     *
     * @param  \PhpSpec\Lumen\Util\Lumen $lumen
     */
    public function __construct(Lumen $lumen)
    {
        $this->lumen = $lumen;
    }

    /**
     * Check if this maintainer applies to the given node.
     *
     * Will check for the `setLumen` method.
     *
     * @param  \PhpSpec\Loader\Node\ExampleNode $example
     * @return boolean
     */
    public function supports(ExampleNode $example)
    {
        return
            $example
                ->getSpecification()
                ->getClassReflection()
                ->hasMethod('setLumen');
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $reflection =
            $example
                ->getSpecification()
                ->getClassReflection()
                ->getMethod('setLumen');

        $reflection->invokeArgs($context, array($this->lumen));
    }

    /**
     * {@inheritdoc}
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }

    /**
     * Give this maintainer a high priority in the stack to ensure that Lumen
     * is bootstrapped early.
     *
     * @return int
     */
    public function getPriority()
    {
        return 1000;
    }
}
