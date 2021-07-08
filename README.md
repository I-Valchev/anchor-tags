# Anchor tags extension

Author: Ivo Valchev

🏷 This Bolt extension adds IDs to HTML elements for use as anchor tags.

Installation:

```bash
composer require ivo-valchev/anchor-tags
```

## Usage

See the `ivovalchev-anchortags.yaml` for all configuration options.

In addition, you can use the `anchor_tags` filter to add anchor tags
to specific HTML, like so:

```twig
{% set html %}
<h2>Hi there</h2>
<p>I am some html.</p>
<strong>It only works on here.</strong>
{% endset %}

{{ html|anchor_tags }}
```


## Running PHPStan and Easy Codings Standard

First, make sure dependencies are installed:

```
COMPOSER_MEMORY_LIMIT=-1 composer update
```

And then run ECS:

```
vendor/bin/ecs check src
```
