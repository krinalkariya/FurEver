<?php
class Response {
  public static function view(string $view, array $data = [], string $layout = 'layout/base'){
    // expose data variables globally to layout + view
    extract($data, EXTR_SKIP);
    $viewPath = __DIR__ . '/../views/'.$view.'.php';
    require __DIR__ . '/../views/'.$layout.'.php';
  }

  public static function redirect(string $to, int $code = 302) {
    $isAbsolute = preg_match('~^https?://~i', $to);
    if (!$isAbsolute) {
      $to = '/' . ltrim($to, '/');                      // normalize
      $to = rtrim(APP_URL, '/') . $to;                  // prefix
    }
    header("Location: $to", true, $code);
    exit;
  }
}
