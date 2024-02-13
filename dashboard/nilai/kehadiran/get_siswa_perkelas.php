<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $id_kelas = $_POST['id'];
    $semester = $_POST['semester'];
    $query = "SELECT * FROM absensi INNER JOIN siswa ON absensi.id_siswa = siswa.id_siswa INNER JOIN semester ON absensi.id_semester = semester.id_semester WHERE (siswa.nama LIKE '$search%' OR siswa.nis LIKE '$search%') AND (siswa.id_kelas = '$id_kelas' AND absensi.id_semester = '$semester') LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $siswa) {
            echo "<a href='../detail/?detail=absen&id=" . $siswa['id_absen'] . "'><div class='search-items'><b>" . $siswa['nama'] . "</b> (" . $siswa['nis'] . ")" . ' ' . $siswa['semester'] . "</div></a>";
        }
    }
} else {
    header("location:./");
}
