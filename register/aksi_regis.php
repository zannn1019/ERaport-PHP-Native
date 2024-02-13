<?php
include '../koneksi.php';
if (isset($_POST["tambah"])) {
  $nama = $_POST["nama"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $no_telp = $_POST['nomor'];
  $password = md5($_POST["password"]);
  $gender = $_POST["gender"];
  $id_role = '1';

  $target_dir = "../asset/img/admin/";
  $target_file = $target_dir . time() . '_' . basename($_FILES["foto"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $foto = basename($target_file);

  if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
    $query = "INSERT INTO admin VALUES (NULL,'$nama','$foto','$email', '$username', '$password', '$gender', '$id_role', current_timestamp() )";
    if (mysqli_query($koneksi, $query)) {
      echo "<script> alert('Registrasi berhasil'); </script>";
      echo "<script> document.location.href='../login/' </script>";
    } else {
      echo "<script> alert('Registrasi Gagal'); </script>";
      echo "<script> document.location.href='./' </script>";
    }
  }

  mysqli_close($koneksi);
}
