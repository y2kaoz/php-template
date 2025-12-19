<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpTemplate;

/** @api class RenderedTemplate */
final readonly class RenderedTemplate implements \Stringable
{
  public function __construct(
    public mixed $returned,
    public string $contents
  ) {}

  #[\Override]
  public function __toString(): string
  {
    return $this->contents;
  }
}
