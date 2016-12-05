<?php /*$password = 'password';
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed; */?>
<?php
    require_once 'core/init.php';
    include 'include/head.php';
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $email = trim($email);
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
    $errors = array();
?>
<?php if($_POST){
  //form validation
  if(empty($_POST['email']) || empty($_POST['password'])){
    $errors[] = 'You must provide email and password';
  }

  //validate email
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = 'You must enter a valid email';
  }

  // password is more that 6 characters
  if(strlen($password) < 6 ){
    $errors[] = 'Password must be at least 6 characters';
  }

  //check if email ixists in the database
  $query = $db->query("SELECT * FROM users WHERE email = '$email'");
  $user = mysqli_fetch_assoc($query);
  $userCount = mysqli_num_rows($query);
  if($userCount < 1){
    $errors[] = 'That email doesn\'t exist in our database';
  }
  if(!password_verify($password, $user['password'])){
    $errors[] = 'The password does not match our records. Please try again';
  }

  //check error
  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    //log user in
    $user_id = $user['id'];
    login($user_id);
  }
} ?>
<style>
  body{
    background: url('../image/headerlogo/bg-pattern.png'), -webkit-linear-gradient(to left, #2D364C, #dc2430);
  	background: url('../image/headerlogo/bg-pattern.png'), linear-gradient(to left, #2D364C, #dc2430);
    background-attachment: fixed;
    /*background-size: 100vw 100vh;*/
  }
</style>
<div id="login-form">
  <div class="rows">
    <div class="col-md-6 log">
      <img src="../image/headerlogo/back-flower.png"/>
    </div>
    <div class="col-md-6">
      <h2 class="text-center">เข้าสู่ระบบผู้ดูแล</h2><hr>
      <form action="login.php" method="POST">
        <div class="form-group">
          <label for="email">อีเมลล์:</label>
          <input type="text" name="email" id="email" class="form-control" value="<?=$email?>"/>
        </div>
        <div class="form-group">
          <label for="password">รหัส:</label>
          <input type="password" name="password" id="password" class="form-control" value="<?=$password?>"/>
        </div>
        <div class="form-group">
          <input type="submit" value="เข้าสู่ระบบ" class="btn btn-primary"/>
        </div>
      </form>
      <p class="text-right"><a href="../index.php" alt="home">หน้าแสดงสินค้า</a></p>
    </div>
  </div>
</div>
<?php include 'include/footer.php'; ?>
