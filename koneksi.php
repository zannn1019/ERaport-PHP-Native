<?php
// Konfigurasi database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eraport";

// Membuat koneksi
$koneksi = mysqli_connect($servername, $username, $password, $dbname);

function randompass($length = 4)
{
    $char = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randompass = '@SMKN2_';
    for ($i = 0; $i <= $length; $i++) {
        $randompass .= $char[rand(0, strlen($char) - 1)];
    }
    return $randompass;
}
