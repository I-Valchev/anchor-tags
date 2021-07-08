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

        $this->config = array_merge_recursive($this->getDefault(), $extension->getConfig()->toArray());

        return $this->config;
    }

    private function getExtension()
    {
        if (! $this->extension) {
            $this->extension = $this->registry->getExtension(Extension::class);
        }

        return $this->extension;
    }

    private function getDefault(): array
    {
        return [
            'tags' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'max_length' => 50,
            'global' => true,
            'append_link' => true,
        ];
    }
}
