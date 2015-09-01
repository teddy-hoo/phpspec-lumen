<?php
namespace PhpSpec\Lumen\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PhpSpec\Event\SpecificationEvent;
use PhpSpec\Lumen\Util\Lumen;

/**
 * This listener is used to setup the Lumen application for each spec.
 *
 * This only applies to specs that implement the LumenBehaviorInterface.
 */
class LumenListener implements EventSubscriberInterface
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
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'beforeSpecification' => array('beforeSpecification', 1)
        );
    }

    /**
     * Run the `beforeSpecification` hook.
     *
     * @param  \PhpSpec\Event\SpecificationEvent $event
     * @return void
     */
    public function beforeSpecification(SpecificationEvent $event)
    {
        $spec = $event->getSpecification();

        if ($spec->getClassReflection()->hasMethod('setLumen')) {
            $this->lumen->refreshApplication();
        }
    }
}
