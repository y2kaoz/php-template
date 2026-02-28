<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpTemplate;

/** @api class RenderedTemplate */
class RenderedTemplate implements \Stringable
{
  public function __construct(
    protected(set) mixed $returned,
    protected(set) string $contents
  ) {}

  #[\Override]
  public function __toString(): string
  {
    return $this->contents;
  }
}
