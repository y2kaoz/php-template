<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpTemplate;

/** @api class VoidElement */
class VoidElement extends Element
{
  /** 
   * @param array<scalar|\Stringable> $attributes 
   * @param list<string> $rawAttributes
   * */
  public function __construct(
    string|\Stringable $tagName,
    array $attributes = [],
    array $rawAttributes = [],
  ) {
    parent::__construct($tagName, '', $attributes, $rawAttributes, true, '');
  }

  #[\Override]
  public function __toString(): string
  {
    return "<{$this->tagName()}{$this->attributes()}>";
  }
}
