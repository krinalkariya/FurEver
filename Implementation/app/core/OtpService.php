<?php
class OtpService {
  public static function create(string $userType, int $userId, string $email, int $ttlMinutes = 10): ?string {
    $otp = (string)random_int(100000, 999999);
    $pdo = DB::conn();
    // Invalidate old unused OTPs
    $pdo->prepare("UPDATE email_otp SET used=1 WHERE user_type=? AND user_id=? AND used=0")->execute([$userType,$userId]);
    $stmt = $pdo->prepare("INSERT INTO email_otp (user_type,user_id,email,otp_hash,expires_at) VALUES (?,?,?,?, DATE_ADD(NOW(), INTERVAL ? MINUTE))");
    $stmt->execute([$userType,$userId,$email,password_hash($otp,PASSWORD_BCRYPT),$ttlMinutes]);
    return $otp;
  }

  public static function verify(string $userType, int $userId, string $email, string $code): bool {
    $pdo = DB::conn();
    $stmt = $pdo->prepare("SELECT * FROM email_otp WHERE user_type=? AND user_id=? AND email=? AND used=0 AND expires_at>NOW() ORDER BY id DESC LIMIT 1");
    $stmt->execute([$userType,$userId,$email]);
    $row = $stmt->fetch();
    if (!$row) return false;

    // attempt limit
    if ((int)$row['attempts'] >= 5) return false;

    $ok = password_verify($code, $row['otp_hash']);
    $pdo->prepare("UPDATE email_otp SET attempts=attempts+1 WHERE id=?")->execute([$row['id']]);

    if ($ok) {
      $pdo->prepare("UPDATE email_otp SET used=1 WHERE id=?")->execute([$row['id']]);
      return true;
    }
    return false;
  }

  public static function resendAllowed(string $userType, int $userId): bool {
    $pdo = DB::conn();
    $stmt = $pdo->prepare("SELECT COUNT(*) c FROM email_otp WHERE user_type=? AND user_id=? AND created_at>DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->execute([$userType,$userId]);
    return ((int)$stmt->fetch()['c']) < 3; // max 3 per hour
  }
}
