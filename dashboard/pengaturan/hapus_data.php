<?php
include '../../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:./../../");
    session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = $_POST['data'];
    try {
        $sql = "DELETE FROM $data";
        $query = mysqli_query($koneksi, $sql);
        if ($query) {
            echo "1";
        }
    } catch (Exception $e) {
        echo "0";
    }
}
