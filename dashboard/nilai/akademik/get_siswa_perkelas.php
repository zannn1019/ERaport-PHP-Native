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
    $query = "SELECT DISTINCT nilai.id_siswa, siswa.*, siswa.nama AS nama_siswa,kelas.*,jurusan.*,semester.* FROM nilai INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan INNER JOIN semester ON nilai.id_semester = semester.id_semester WHERE (siswa.nama LIKE '$search%' OR siswa.nis LIKE '$search%') AND (siswa.id_kelas = '$id_kelas' AND nilai.id_semester = '$semester') LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $siswa) {
            echo "<a href='../detail/?detail=nilai&id=" . $siswa['id_siswa'] . "&semester=$semester'><div class='search-items'><b>" . $siswa['nama'] . "</b> (" . $siswa['nis'] . ") " . $siswa['semester'] . "</div></a>";
        }
    }
} else {
    header("location:./");
}
