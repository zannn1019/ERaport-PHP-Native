<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login ERaport</title>
  <link rel="stylesheet" href="../asset/CSS/login.css">
</head>

<body>
  <div class="center">
    <h1>LOGIN</h1>

    <form method="post" action="../ceklogin.php">
      <div class="txt_field">
        <input type="text" name="username" required>
        <span></span>
        <label>Username</label>
      </div>
      <div class="txt_field">
        <input type="password" name="password" id="password" required>
        <span></span>
        <label>Password</label>
      </div>
      <input type="submit" value="Login" name="login-admin">
      <div class="signup_link">
        Tidak punya akun ?<a href="../register/"> Daftar</a>
      </div>
    </form>
  </div>
  <div class="wave">
    <img alt="foto" src="../asset/img/wave.png" alt="" draggable="false">
  </div>
</body>

</html>