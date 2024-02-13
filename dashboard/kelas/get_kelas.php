<?php
include '../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT full_kelas,id_kelas FROM (
        SELECT id_kelas,CONCAT(tingkatan,' ',jurusan,' ',nama_kelas) as full_kelas
        FROM `kelas` 
        INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan
    ) AS kelas_full
    WHERE full_kelas LIKE '$search%'
    ";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $kelas) {
            echo "<a href='../aksi/edit.php?edit=kelas&id=" . $kelas['id_kelas'] . "'><div class='search-items'><b>" . $kelas['full_kelas'] . "</b></div></a>";
        }
    }
} else {
    header("location:./");
}
