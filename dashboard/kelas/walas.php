<?php
include '../../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:../../../");
    session_destroy();
}
$query = $_GET['key'];

$sql = "SELECT * FROM guru WHERE nama LIKE '%$query%' AND id_guru NOT IN (SELECT wali_kelas FROM kelas WHERE wali_kelas IS NOT NULL)";

$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="walas-result">' .  $row['nama'] . "</div>";
    }
} else {
    echo '<div class="walas-no-result">Tidak ada hasil pencarian</div>';
}
