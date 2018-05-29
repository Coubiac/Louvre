<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    //Permet de récupérer la langue du navigateur et ainsi d'adapter la traduction du site
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $language = $request->getLanguages();

        if (preg_match('#^fr#', $language[0])) {
            $request->setLocale('fr');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 15]],
        ];
    }
}
