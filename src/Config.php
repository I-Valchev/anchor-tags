<?php

namespace IvoValchev\AnchorTags;

use Bolt\Extension\ExtensionRegistry;

class Config
{
    /** @var  */
    private $config;

    /** @var ExtensionRegistry */
    private $registry;

    /** @var Extension|null */
    private $extension;

    public function __construct(ExtensionRegistry $registry)
    {

        $this->registry = $registry;
    }

    public function getConfig(): array
    {
        if ($this->config) {
            return $this->config;
        }

        $extension = $this->getExtension();

        $this->config = $extension->getConfig();

        return $this->config;
    }

    private function getExtension()
    {
        return  $this->extension = $this->registry->getExtension(Extension::class);
    }
}
