<?php
class AuthController {

public function login() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->render('auth/login', ['title' => 'Login']);
    }

    if (!CSRF::check($_POST['_csrf'] ?? null)) {
        Session::flash('error','Invalid session');
        return Response::redirect('login');
    }

    $emailOrUser = trim($_POST['email'] ?? '');
    $pass        = (string)($_POST['password'] ?? '');
    $role        = $_POST['role'] ?? 'user';
    $pdo         = DB::conn();

    // pick correct query
    if ($role === 'admin') {
        $stmt = $pdo->prepare("SELECT admin_id AS id, username AS name, password FROM admin WHERE username=?");
    } elseif ($role === 'ngo') {
        $stmt = $pdo->prepare("SELECT ngo_id AS id, name, password, status, email_verified FROM ngo WHERE email=?");
    } else {
        $stmt = $pdo->prepare("SELECT user_id AS id, name, password, status, email_verified FROM user_account WHERE email=?");
    }

    $stmt->execute([$emailOrUser]);
    $row = $stmt->fetch();

    // CASE 1: username/email not found
    if (!$row) {
        return $this->render('auth/login', [
            'title'  => 'Login',
            'errors' => [
                'email' => $role === 'admin' 
                    ? 'Invalid username.' 
                    : 'Invalid email.'
            ],
            'email'  => $emailOrUser,
            'role'   => $role,
        ]);
    }

    // NGO extra checks
    if ($role === 'ngo') {
        if ((int)$row['email_verified'] !== 1) {
            Session::flash('error','Please verify your email');
            return Response::redirect('verify?role=ngo&email='.urlencode($emailOrUser));
        }
        if ($row['status'] !== 'approved') {
            Session::flash('error','NGO not approved yet');
            return Response::redirect('login');
        }
    }

    // USER extra checks
    if ($role === 'user') {
        if ((int)$row['email_verified'] !== 1) {
            Session::flash('error','Please verify your email');
            return Response::redirect('verify?role=user&email='.urlencode($emailOrUser));
        }
        if ($row['status'] !== 'active') {
            Session::flash('error','Account inactive');
            return Response::redirect('login');
        }
    }

    // CASE 2: wrong password (verify hashed password)
    if (!password_verify($pass, $row['password'])) {
        return $this->render('auth/login', [
            'title'  => 'Login',
            'errors' => ['password' => 'Invalid password.'],
            'email'  => $emailOrUser,
            'role'   => $role,
        ]);
    }

    // ✅ success → finish
    return $this->finish($row, $pass, $role);
}

/**
 * Verify password, set session, and redirect by role.
 */
private function finish($row, string $plainPassword, string $role){
  Session::set('role', $role);

  if ($role === 'admin') {
    Session::set('admin_id', (int)$row['id']);
    Session::set('admin_name', $row['name'] ?? 'Admin');
    Session::set('admin', [
      'role'      => 'admin',
      'admin_id'  => (int)$row['id'],
      'name'      => $row['name'] ?? 'Admin',
    ]);
    return Response::redirect('admin/dashboard');
  }

  if ($role === 'ngo') {
    Session::set('ngo_id', (int)$row['id']);
    Session::set('ngo_name', $row['name'] ?? '');
    Session::set('ngo', [
      'role'           => 'ngo',
      'ngo_id'         => (int)$row['id'],
      'name'           => $row['name'] ?? '',
      'status'         => $row['status'] ?? 'approved',
      'email_verified' => (int)($row['email_verified'] ?? 1),
    ]);
    return Response::redirect('ngo/dashboard');
  }

  // user
  Session::set('user_id', (int)$row['id']);
  Session::set('user_name', $row['name'] ?? '');
  Session::set('user', [
    'role'           => 'user',
    'user_id'        => (int)$row['id'],
    'name'           => $row['name'] ?? '',
    'status'         => $row['status'] ?? 'active',
    'email_verified' => (int)($row['email_verified'] ?? 1),
  ]);
  return Response::redirect('user/dashboard');
}

private function render($view, $data = []) {
    extract($data);
    return Response::view($view, $data, 'layout/auth_base');
}

public function registerUser(){
    if ($_SERVER['REQUEST_METHOD']!=='POST') return $this->render('auth/register_user', ['title'=>'Create User']);
    if (!CSRF::check($_POST['_csrf'] ?? null)) return $this->back('Invalid session');

    $name = trim($_POST['name']??''); $city=trim($_POST['city']??'');
    $email= trim($_POST['email']??''); $phone=trim($_POST['phone']??'');
    $pass = (string)($_POST['password']??'');

    $errors=[];
    if(!Validator::str($name,2,100))   $errors['name']='Enter a valid name (2–100 chars).';
    if(!Validator::str($city,2,50))    $errors['city']='Enter a valid city (2–50 chars).';
    if(!Validator::email($email))      $errors['email']='Enter a valid email.';
    if(!Validator::phone($phone))      $errors['phone']='Enter a valid phone (7–15 digits).';
    if(!Validator::password($pass))    $errors['password']='8+ chars with letter, number, and symbol.';

    $pdo = DB::conn();
    if(empty($errors)){
      $stmt = $pdo->prepare("
        SELECT 1 FROM user_account WHERE email=? 
        UNION SELECT 1 FROM ngo WHERE email=? 
        UNION SELECT 1 FROM pending_registration WHERE email=? AND role='user'
      ");
      $stmt->execute([$email,$email,$email]);
      if($stmt->fetch()) $errors['email'] = 'Email already in use.';

      $stmt = $pdo->prepare("
        SELECT 1 FROM user_account WHERE phone=? 
        UNION SELECT 1 FROM ngo WHERE phone=? 
        UNION SELECT 1 FROM pending_registration WHERE phone=? AND role='user'
      ");
      $stmt->execute([$phone,$phone,$phone]);
      if($stmt->fetch()) $errors['phone'] = 'Phone already in use.';
    }

    if($errors){
      return $this->render('auth/register_user', [
        'title'  => 'Create User',
        'errors' => $errors,
        'name'   => $name,
        'city'   => $city,
        'email'  => $email,
        'phone'  => $phone,
      ]);
    }

    $otp = (string)random_int(100000,999999);
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
    $pdo->prepare("DELETE FROM pending_registration WHERE email=? AND role='user'")->execute([$email]);
    $stmt=$pdo->prepare("INSERT INTO pending_registration (role,name,email,phone,city,password,otp,expires_at) VALUES ('user',?,?,?,?,?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
    $stmt->execute([$name,$email,$phone,$city,$hashedPassword,$otp]);

    if(!MailService::sendOtp($email,$otp)){
      $pdo->prepare("DELETE FROM pending_registration WHERE email=? AND role='user'")->execute([$email]);
      return $this->back('Could not send OTP. Try again.');
    }

    Response::redirect("verify?role=user&email=".urlencode($email));
}

public function registerNgo(){
    if ($_SERVER['REQUEST_METHOD']!=='POST') return $this->render('auth/register_ngo', ['title'=>'Register NGO']);
    if (!CSRF::check($_POST['_csrf'] ?? null)) return $this->back('Invalid session');

    $name = trim($_POST['name']??''); $city=trim($_POST['city']??'');
    $email= trim($_POST['email']??''); $phone=trim($_POST['phone']??'');
    $pass = (string)($_POST['password']??'');

    $errors=[];
    if(!Validator::str($name,2,100))   $errors['name']='Enter a valid NGO name (2–100).';
    if(!Validator::str($city,2,50))    $errors['city']='Enter a valid city.';
    if(!Validator::email($email))      $errors['email']='Enter a valid email.';
    if(!Validator::phone($phone))      $errors['phone']='Enter a valid phone.';
    if(!Validator::password($pass))    $errors['password']='8+ chars with letter, number, and symbol.';

    $pdo=DB::conn();
    if(empty($errors)){
      $stmt = $pdo->prepare("
        SELECT 1 FROM ngo WHERE email=? 
        UNION SELECT 1 FROM user_account WHERE email=? 
        UNION SELECT 1 FROM pending_registration WHERE email=? AND role='ngo'
      ");
      $stmt->execute([$email,$email,$email]);
      if($stmt->fetch()) $errors['email'] = 'Email already in use.';

      $stmt = $pdo->prepare("
        SELECT 1 FROM ngo WHERE phone=? 
        UNION SELECT 1 FROM user_account WHERE phone=? 
        UNION SELECT 1 FROM pending_registration WHERE phone=? AND role='ngo'
      ");
      $stmt->execute([$phone,$phone,$phone]);
      if($stmt->fetch()) $errors['phone'] = 'Phone already in use.';
    }

    if($errors){
      return $this->render('auth/register_ngo', [
        'title'  => 'Register NGO',
        'errors' => $errors,
        'name'   => $name,
        'city'   => $city,
        'email'  => $email,
        'phone'  => $phone,
      ]);
    }

    $otp=(string)random_int(100000,999999);
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
    $pdo->prepare("DELETE FROM pending_registration WHERE email=? AND role='ngo'")->execute([$email]);
    $stmt=$pdo->prepare("INSERT INTO pending_registration (role,name,email,phone,city,password,otp,expires_at) VALUES ('ngo',?,?,?,?,?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
    $stmt->execute([$name,$email,$phone,$city,$hashedPassword,$otp]);

    if(!MailService::sendOtp($email,$otp)){
      $pdo->prepare("DELETE FROM pending_registration WHERE email=? AND role='ngo'")->execute([$email]);
      return $this->back('Could not send OTP. Try again.');
    }
    Response::redirect("verify?role=ngo&email=".urlencode($email));
}

private function back($msg){ Session::flash('error',$msg); return Response::redirect('login'); }

public function verify(){
    if (!CSRF::check($_POST['_csrf'] ?? null)) return $this->back('Invalid session');
    $email=trim($_POST['email']??''); $role=$_POST['role']??'user'; $code=trim($_POST['code']??'');

    if(!preg_match('/^\d{6}$/',$code)) return Response::redirect("verify?role=$role&email=".urlencode($email));

    $pdo=DB::conn();
    $stmt=$pdo->prepare("SELECT * FROM pending_registration WHERE email=? AND role=? AND expires_at>NOW() ORDER BY id DESC LIMIT 1");
    $stmt->execute([$email,$role]);
    $p=$stmt->fetch();
    if(!$p){ Session::flash('error','Expired/invalid code.'); return Response::redirect("verify?role=$role&email=".urlencode($email)); }

    if($p['attempts']>=5){ Session::flash('error','Too many attempts.'); return Response::redirect("verify?role=$role&email=".urlencode($email)); }

    $pdo->prepare("UPDATE pending_registration SET attempts=attempts+1 WHERE id=?")->execute([$p['id']]);
    if($code !== $p['otp']){
      Session::flash('error','Incorrect code.');
      return Response::redirect("verify?role=$role&email=".urlencode($email));
    }

    if($role==='ngo'){
      $ins=$pdo->prepare("INSERT INTO ngo (name,email,password,phone,city,status,email_verified) VALUES (?,?,?,?,?,'pending',1)");
      $ins->execute([$p['name'],$p['email'],$p['password'],$p['phone'],$p['city']]);
    } else {
      $ins=$pdo->prepare("INSERT INTO user_account (name,email,password,phone,city,status,email_verified) VALUES (?,?,?,?,?,'active',1)");
      $ins->execute([$p['name'],$p['email'],$p['password'],$p['phone'],$p['city']]);
    }
    $pdo->prepare("DELETE FROM pending_registration WHERE id=?")->execute([$p['id']]);

    Session::flash('success', $role==='ngo' ? 'Email verified. Await admin approval to login.' : 'Email verified. You can now log in.');
    Response::redirect('login');
}

public function resendOtp(){
    if (!CSRF::check($_POST['_csrf'] ?? null)) return $this->back('Invalid session');
    $email=trim($_POST['email']??''); $role=$_POST['role']??'user';
    $pdo=DB::conn();
    $row=$pdo->prepare("SELECT id FROM pending_registration WHERE email=? AND role=? ORDER BY id DESC LIMIT 1");
    $row->execute([$email,$role]); $p=$row->fetch();
    if(!$p){ Session::flash('error','No pending verification found.'); return Response::redirect('login'); }

    $cnt=$pdo->prepare("SELECT COUNT(*) c FROM pending_registration WHERE email=? AND role=? AND created_at>DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $cnt->execute([$email,$role]); if((int)$cnt->fetch()['c']>=3){ Session::flash('error','Too many requests. Try later.'); return Response::redirect("verify?role=$role&email=".urlencode($email)); }

    $otp=(string)random_int(100000,999999);
    $pdo->prepare("UPDATE pending_registration SET otp=?, expires_at=DATE_ADD(NOW(), INTERVAL 10 MINUTE), attempts=0 WHERE id=?")->execute([$otp,$p['id']]);
    MailService::sendOtp($email,$otp);
    Session::flash('success','New OTP sent.');
    Response::redirect("verify?role=$role&email=".urlencode($email));
}

public function ngoStatus(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return Response::redirect('ngo/status');
    if (!CSRF::check($_POST['_csrf'] ?? null)) { Session::flash('error','Invalid session'); return Response::redirect('ngo/status'); }

    $email = trim($_POST['email'] ?? '');
    if (!Validator::email($email)) { Session::flash('error','Enter a valid email'); return Response::redirect('ngo/status'); }

    $pdo = DB::conn();
    $stmt = $pdo->prepare("SELECT status, email_verified FROM ngo WHERE email=?");
    $stmt->execute([$email]);
    if ($row = $stmt->fetch()){
      $msg = "Status: {$row['status']}";
      if ((int)$row['email_verified'] !== 1) $msg .= " (Email not verified)";
      Session::flash('success', $msg);
      return Response::redirect('ngo/status');
    }

    $stmt = $pdo->prepare("SELECT expires_at FROM pending_registration WHERE email=? AND role='ngo' ORDER BY id DESC LIMIT 1");
    $stmt->execute([$email]);
    if ($p = $stmt->fetch()){
      Session::flash('success','Your registration is pending email verification. Please check your inbox for OTP (or resend from verify screen).');
      return Response::redirect('ngo/status');
    }

    Session::flash('error','No NGO registration found for this email.');
    return Response::redirect('ngo/status');
}

public function logout(): void {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    Session::destroy();
    session_unset();
    session_destroy();
    Response::redirect(APP_URL . '/');
    exit;
}
}
