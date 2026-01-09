<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../vendor/autoload.php';

class MailService {
  public static function sendOtp(string $toEmail, string $otp): bool {
    $m = new PHPMailer(true);
    // (Optional) disable verbose debug in production
    // $m->SMTPDebug = 0;

    try {
      $m->isSMTP();
      $m->Host       = SMTP_HOST;
      $m->SMTPAuth   = true;
      $m->Username   = SMTP_USER;
      $m->Password   = SMTP_PASS;
      $m->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $m->Port       = SMTP_PORT;

      $m->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
      $m->addAddress($toEmail);

      $m->isHTML(true);
      $m->Subject = 'FurEver: Verify your email';
      $m->Body    = "<p>Your OTP is <strong>{$otp}</strong>. It expires in 10 minutes.</p>";
      $m->AltBody = "Your OTP is {$otp}. It expires in 10 minutes.";

      return $m->send();
    } catch (Exception $e) {
      error_log('Mail error: '.$m->ErrorInfo);
      return false;
    }
  }
}
