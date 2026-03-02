<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpTemplate;

/** @api class Element */
class Element implements \Stringable
{
  protected function cleanContents(string|\Stringable $value): string
  {
    return ($this->rawContents || $value instanceof Element)
      ? strval($value)
      : htmlspecialchars(strval($value), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', false);
  }

  /** 
   * @param string|\Stringable|list<string|\Stringable> $contents
   * @param array<scalar|\Stringable> $attributes
   * @param list<string> $rawAttributes
   */
  public function __construct(
    protected(set) string|\Stringable $tagName,
    protected(set) string|\Stringable|array $contents = '',
    protected(set) array $attributes = [],
    protected(set) array $rawAttributes = [],
    protected(set) bool $rawContents = false,
    protected(set) string $spaces = "  ",
  ) {}

  public function tagName(): string
  {
    return trim(htmlspecialchars(strval($this->tagName), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', false));
  }

  public function contents(): string
  {
    $contents = is_array($this->contents)
      ? implode("\n", array_map($this->cleanContents(...), $this->contents))
      : $this->cleanContents($this->contents);
    return str_contains($contents, "\n")
      ? PHP_EOL . $this->spaces . str_replace(PHP_EOL, PHP_EOL . $this->spaces, $contents) . PHP_EOL
      : trim($contents);
  }

  public function attributes(): string
  {
    $render =
      function (int|string $key, bool|float|int|string|null|\Stringable $value): string {
        if(is_bool($value)) {
          $value = $value ? 'true' : 'false';
        }
        if (is_string($key)) {
          if (!in_array($key, $this->rawAttributes)) {
            $key   = htmlspecialchars(trim($key), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', false);
            $value = htmlspecialchars(trim(strval($value)), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', false);
          } else {
            $value = strtr(strval($value), '"', "'");
          }
          return "{$key}=\"{$value}\"";
        }
        return "{$value}";
      };
    return empty($this->attributes)
      ? ''
      : ' ' . implode(
        ' ',
        array_map($render(...), array_keys($this->attributes), $this->attributes)
      );
  }

  #[\Override]
  public function __toString(): string
  {
    $tagName = $this->tagName();
    $attributes = $this->attributes();
    $contents = $this->contents();
    return ($tagName==='') ? $contents : "<{$tagName}{$attributes}>{$contents}</{$tagName}>";
  }
}
