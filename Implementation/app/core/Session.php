 <?php
class Session {
  public static function start(): void {
    if (session_status() === PHP_SESSION_NONE) {
      session_name(SESSION_NAME);
      session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax',
        'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on']);
      session_start();
    }
  }
  public static function set($k,$v){ $_SESSION[$k]=$v; }
  public static function get($k,$d=null){ return $_SESSION[$k]??$d; }
  public static function flash($k,$v=null){
    if($v!==null){ $_SESSION['_flash'][$k]=$v; return; }
    $m=$_SESSION['_flash'][$k]??null; unset($_SESSION['_flash'][$k]); return $m;
  }
  function requireRole(string $role) {
    if (Session::get('role') !== $role) {
        Response::redirect('/login');
        exit;
    }
}

  public static function destroy(){ $_SESSION=[]; session_destroy(); }
}

