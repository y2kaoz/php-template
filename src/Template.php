<?php

declare(strict_types=1);

namespace Y2KaoZ\PhpTemplate;

/** @api class Template */
class Template implements \Stringable
{
  /** 
   * @param array<non-empty-string,mixed> $templateParams 
   * @param false|int $extract Do not use extract on untrusted data, like raw user input.
   */
  private static function renderTemplate(
    string $templatePath,
    array $templateParams,
    false|int $extract,
    string $prefix
  ): RenderedTemplate {
    if (isset($templateParams['templatePath'])) {
      throw new \Exception("The parameters contain templatePath.");
    }
    if (!file_exists($templatePath) || !is_readable($templatePath)) {
      throw new \Exception("The Template '{$templatePath}' doesn't exist or is not readable.");
    }
    if (ob_start() === false) {
      throw new \Exception("Failure starting output buffer.");
    }
    if ($extract !== false) {
      assert(in_array($extract, [0, 1, 2, 3, 4, 5, 6, 7, 256, 257, 258, 259, 260, 261, 262, 263]));
      extract(
        (function () use ($templateParams): array {
          $p = $templateParams;
          unset($templateParams);
          return $p;
        })(),
        (function () use ($extract): int {
          $e = $extract;
          unset($extract);
          return $e;
        })(),
        (function () use ($prefix): string {
          $p = $prefix;
          unset($prefix);
          return $p;
        })()
      );
    } else {
      unset($extract, $prefix);
    }
    /** @var mixed $returned */
    $returned = include $templatePath;
    $contents = ob_get_clean();
    if ($contents === false) {
      throw new \Exception("Error getting or cleaning the buffer contents.");
    }
    return new RenderedTemplate($returned, $contents);
  }

  /** 
   * @param array<non-empty-string,mixed> $params
   */
  public function __construct(
    public string $path,
    public array $params = [],
    public string $spaces = '  ',
    public false|int $extract = EXTR_SKIP,
    public string $prefix = "template"
  ) {
    if (!file_exists($path) || !is_readable($path)) {
      throw new \Exception("The Template '{$path}' doesn't exist or is not readable.");
    }
  }

  /** @param array<non-empty-string,mixed> $params */
  public function render(
    null|array $params = null,
    null|string $spaces = null,
    null|false|int $extract = null,
    null|string $prefix = null
  ): RenderedTemplate {
    try {
      $spaces  ??= $this->spaces;
      $params  ??= $this->params;
      $extract ??= $this->extract;
      $prefix  ??= $this->prefix;

      $renderedTemplate = self::renderTemplate($this->path, $params, $extract, $prefix);

      if (empty($spaces)) {
        return $renderedTemplate;
      }

      return new RenderedTemplate(
        $renderedTemplate->returned,
        str_replace(PHP_EOL, PHP_EOL . $spaces, $renderedTemplate->contents)
      );
    } catch (\Throwable $t) {
      ob_clean();
      throw $t;
    }
  }

  #[\Override]
  public function __toString(): string
  {
    return $this->render()->contents;
  }
}
