<?php

// src/AppBundle/EventSubscriber/LocaleSubscriber.php
namespace AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct()
    {
        $this->defaultLocale = 'en';
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // dump($request->getClientIp());
        $ip_data = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$request->getClientIp()));
        // dump($request->getClientIp());
        // dump($ip_data);
        if ($ip_data) {
            if($ip_data->geoplugin_countryCode == 'TN') {
                $this->defaultLocale = 'fr';
            } else {
                $this->defaultLocale = 'en';
            }
            // dump('ip locale: '. $this->defaultLocale);
        } else {
            $this->defaultLocale = 'fr';
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
            // dump("request with locale: ". $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
            // dump("session loc: ".$request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}


?>