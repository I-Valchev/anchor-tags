<?php

namespace IvoValchev\AnchorTags;

class Parser
{
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function parse(string $html): string
    {
        $tags = $this->config->getConfig()['tags'];

        dump("HI THERE");
        return $html;
    }
}
