<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register ERaport</title>
  <link rel="stylesheet" href="../asset/CSS/register.css">
</head>

<body>
  <div class="container">
    <div class="title">Registration</div>
    <div class="content">
      <form action="aksi_regis.php" method="post" enctype="multipart/form-data">
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="nama" minlength="5" max="100" placeholder="Enter your name" required>
          </div>
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="username" minlength="8" max="20" placeholder="Enter your username" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="text" name="email" placeholder="Enter your email" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" minlength="12" maxlength="15" pattern="[0-9]+" name="nomor" placeholder="Enter your number" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter your password" id="pass" required>
          </div>
          <div class="input-box">
            <span class="details confirm-txt">Confirm Password</span>
            <input type="password" name="confirm_password" placeholder="Confirm your password" id="confirm_pass" required>
          </div>
          <div class="input-box">
            <div class="gender-details" name="gender">
              <input type="radio" name="gender" id="dot-1" value="1">
              <input type="radio" name="gender" id="dot-2" value="2">
              <span class="gender-title">Gender</span>
              <div class="category">
                <label for="dot-1">
                  <span class="dot one"></span>
                  <span class="gender">Male</span>
                </label>
                <label for="dot-2">
                  <span class="dot two"></span>
                  <span class="gender">Female</span>
                </label>
              </div>
            </div>
          </div>
          <div class="input-box input-file">
            <span class="details">Photo Profile</span>
            <input type="file" name="foto" id="" accept="image/*">
          </div>
          <div class="button">
            <input type="submit" name="tambah" value="Register" id="submit-btn">
            <div class="login_link">
              Sudah punya akun ? <a href="../login/">Login</a>
            </div>
          </div>
      </form>

    </div>
  </div>
  <script src="../asset/jquery-3.6.4.min.js"></script>
  <script>
    $("#pass").keyup(function() {
      var password = $("#pass").val();
      var confirm = $("#confirm_pass").val();
      if (password != confirm) {
        $("#confirm_pass").css("border", "1px solid red");
        $(".confirm-txt").css("color", "red");
        $("#submit-btn").attr("disabled", true);
        $("#submit-btn").css("cursor", "not-allowed");
      }
    })
    $("#confirm_pass").keyup(function() {
      var password = $("#pass").val();
      var confirm = $("#confirm_pass").val();
      if (password == confirm) {
        $("#confirm_pass").css("border", "1px solid white");
        $(".confirm-txt").css("color", "");
        $("#submit-btn").attr("disabled", false);
        $("#submit-btn").css("cursor", "pointer");
      }
    })
  </script>
</body>

</html>