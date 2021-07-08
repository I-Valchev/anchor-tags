<?php

namespace IvoValchev\AnchorTags;

use Bolt\Entity\FieldInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    /** @var Parser */
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters()
    {
        $safe = [
            'is_safe' => ['html'],
        ];

        return [
            new TwigFilter('anchor_tags', [$this, 'addAnchorTags'], $safe)
        ];
    }

    public function addAnchorTags($object): string
    {
        if ($object instanceof FieldInterface) {
            $object = $object->__toString();
        } else if (! is_string($object)) {
            $object = (string) $object;
        }

        return $this->parser->parse($object);
    }
}
