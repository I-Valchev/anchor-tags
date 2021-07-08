<?php

declare(strict_types=1);

namespace IvoValchev\AnchorTags;

use Bolt\Extension\ExtensionController;

class AppendLinkParser extends ExtensionController
{
    public function generate(string $link): string
    {
        return $this->renderView('@anchor-tags/append_link.twig', [
            'link' => '#' . $link,
        ]);
    }
}
