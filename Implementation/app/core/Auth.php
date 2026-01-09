 <?php
class Auth {
  public static function login(array $row, string $role): void {
    Session::start();
    Session::set('auth', ['id'=>$row['id'], 'name'=>$row['name']??'', 'role'=>$role]);
  }
  public static function user(){ Session::start(); return Session::get('auth'); }
  public static function role(){ $u=self::user(); return $u['role']??null; }
  public static function logout(){ Session::start(); Session::destroy(); }
}

