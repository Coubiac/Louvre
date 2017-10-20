<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleSubscriber implements EventSubscriberInterface
{

    public function onKernelRequest(GetResponseEvent $event)
    {

        $request = $event->getRequest();
        $language = $request->getLanguages();

        if (preg_match("#^fr#", $language[0])) {

            $request->setLocale('fr');

        }


    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}
