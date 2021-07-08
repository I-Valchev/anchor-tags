<?php

namespace IvoValchev\AnchorTags;

use Bolt\Common\Str;
use Cocur\Slugify\Slugify;
use Twig\Environment;

class Parser
{
    /** @var Config */
    private $config;

    /** @var Slugify */
    private $slugify;

    /** @var Environment */
    private $twig;

    /** @var AppendLinkParser */
    private $appendLinkParser;

    public function __construct(Config $config, Environment $twig, AppendLinkParser $appendLinkParser)
    {
        $this->config = $config;
        $this->slugify = Slugify::create();
        $this->twig = $twig;
        $this->appendLinkParser = $appendLinkParser;
    }

    public function parse(string $html): string
    {
        $tags = $this->config->getConfig()['tags'];

        foreach($tags as $tag) {
            $regex = $this->getRegex($tag);
            preg_match_all($regex, $html,$attributes);

            $htmlTags = array_shift($attributes);
            $html = $this->handleTags($tag, $htmlTags, $attributes, $html);
        }

        return $html;
    }

    private function handleTags(string $tag, array $htmlTags, array $attributes, string $html): string
    {
        if (empty($htmlTags)) {
            return $html;
        }

        $newTags = [];
        $oldTags = [];
        foreach ($htmlTags as $key => $htmlTag) {
            $newTags[] = $this->handleTag($tag, $htmlTag, $attributes[$key] ?? []);
            $oldTags[] = $htmlTag;
        }

        return str_replace($oldTags, $newTags, $html);
    }

    private function handleTag(string $tag, string $htmlTag, array $attributes): string
    {
        foreach ($attributes as $attribute) {
            if (Str::startsWith($attribute, 'id')) {
                return $htmlTag; // bail out. We don't change existing IDs.
            }
        }

        $id = $this->generateIdFromHtml($htmlTag);

        $htmlTag = $this->handleAnchorLink($tag, $htmlTag, $id);

        return str_replace($this->getOpenTag($tag), $this->getOpenTagWithId($tag, $id), $htmlTag);
    }

    private function handleAnchorLink(string $tag, string $htmlTag, string $id)
    {
        if ($this->config->getConfig()['append_link'] === false) {
            return $htmlTag;
        }

        $anchorLink = $this->appendLinkParser->generate($id);
        $htmlTag = str_replace($this->getCloseTag($tag), $this->getCloseTagWithAnchorLink($tag, $anchorLink), $htmlTag);

        return $htmlTag;
    }

    private function generateIdFromHtml(string $html): string
    {
        $text = strip_tags($html);
        $maxLength = $this->config->getConfig()['max_length'];

        $text = substr($text, 0, $maxLength);
        $text = $this->slugify->slugify($text);

        return $text;
    }

    private function getOpenTag(string $tag): string
    {
        return sprintf('<%s', $tag);
    }

    private function getOpenTagWithId(string $tag, string $id): string
    {
        return sprintf("%s %s", $this->getOpenTag($tag),
            sprintf("id='%s'", $id)
        );
    }

    private function getCloseTag(string $tag): string
    {
        return sprintf('</%s>', $tag);
    }

    private function getCloseTagWithAnchorLink(string $tag, string $anchorLink): string
    {
        return sprintf("%s%s", $anchorLink, $this->getCloseTag($tag));
    }

    private function getRegex(string $tag): string
    {
        return sprintf("/<%s\s*(.*?)>\s*.*?<\/%s>/", $tag, $tag);
    }
}
