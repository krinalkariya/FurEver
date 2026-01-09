<?php
class Validator {
  public static function email($v){ return filter_var($v, FILTER_VALIDATE_EMAIL); }
  public static function str($v,$min,$max){ $l=mb_strlen(trim($v)); return $l>=$min && $l<=$max; }
public static function phone($v){ return preg_match('/^\+?\d{7,15}$/',$v); }

  public static function password($v){
    return (bool)preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#^._-])[A-Za-z\d@$!%*?&#^._-]{8,64}$/',$v);
  }
}
