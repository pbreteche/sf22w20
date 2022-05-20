<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $browserLocale = $request->getPreferredLanguage(['fr', 'en']);
        $queryStringLocale = $request->query->get('locale');
        $session = $request->getSession();
        if ($queryStringLocale) {
            $session->set('locale', $queryStringLocale);
        }
        $sessionLocale = $session->get('locale');

        $request->setLocale($sessionLocale ?? $browserLocale);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => ['onKernelRequest', 28],
        ];
    }
}
