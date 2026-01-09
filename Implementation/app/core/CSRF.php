<?php
class CSRF {
  public static function token(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
  }
  public static function check(?string $token): bool {
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $stored = $_SESSION['csrf_token'] ?? '';
    return $token && $stored && hash_equals($stored, $token);
  }
  public static function input(): string {
    return '<input type="hidden" name="_csrf" value="'.htmlspecialchars(self::token(), ENT_QUOTES).'">';
  }
}

