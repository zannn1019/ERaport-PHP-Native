<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['id_jurusan'])) {
    $id_jurusan = $_POST['id_jurusan'];

    $query = "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE kelas.id_jurusan = '$id_jurusan'";
    $result = mysqli_query($koneksi, $query);

    $kelas = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (count($kelas) > 0) {
        foreach ($kelas as $row) {
            echo "<option value='" . $row['id_kelas'] . "'>" . $row['tingkatan'] . ' ' . $row['jurusan'] . ' ' . $row['nama_kelas'] . "</option>";
        }
    } else {
        echo "<option value=''>Pilih Kelas</option>";
    }
} else {
    header("location:./");
}
