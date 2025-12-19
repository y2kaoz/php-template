<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpTemplate;

/** @api class TemplateCollection */
final readonly class TemplateCollection
{
  /** 
   * @param array<non-empty-string,mixed> $globals
   * @param array<non-empty-string,mixed> $params
   */
  public function __construct(
    public string $templateRoot,
    public array $params = [],
    public string $spaces = '  ',
    public array $globals = [],
    public false|int $extract = EXTR_SKIP,
    public string $prefix = "template"
  ) {
    if (!file_exists($templateRoot) || !is_dir($templateRoot) || !is_readable($templateRoot)) {
      throw new \Exception("The Template root directory '{$templateRoot}' is not a readable directory.");
    }
  }

  /** 
   * @param array<non-empty-string,mixed> $globals
   * @param array<non-empty-string,mixed> $params
   */
  public function getTemplate(
    string $relativePath,
    null|array $params = null,
    null|string $spaces = null,
    null|array $globals = null,
    null|false|int $extract = null,
    null|string $prefix = null
  ): Template {
    return new Template(
      "{$this->templateRoot}/{$relativePath}",
      array_merge($globals ?? $this->globals, $params ?? $this->params),
      $spaces ?? $this->spaces,
      $extract ?? $this->extract,
      $prefix ?? $this->prefix
    );
  }

  /** 
   * @param array<non-empty-string,mixed> $globals
   * @param array<non-empty-string,mixed> $params
   */
  public function render(
    string $relativePath,
    null|array $params = null,
    null|string $spaces = null,
    null|array $globals = null,
    null|false|int $extract = null,
    null|string $prefix = null
  ): RenderedTemplate {
    return $this->getTemplate(
      $relativePath,
      $params,
      $spaces,
      $globals,
      $extract,
      $prefix
    )->render();
  }
}
