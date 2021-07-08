<?php

declare(strict_types=1);

namespace IvoValchev\AnchorTags;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseSubscriber implements EventSubscriberInterface
{
    public const PRIORITY = 0;

    /** @var Parser */
    private $parser;

    /** @var Config */
    private $config;

    public function __construct(Parser $parser, Config $config)
    {
        $this->parser = $parser;
        $this->config = $config;
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($this->config->getConfig()['global'] === false) {
            return;
        }

        $response = $event->getResponse();
        $content = $response->getContent();

        $response->setContent($this->parser->parse($content));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [['onKernelResponse', self::PRIORITY]],
        ];
    }
}
