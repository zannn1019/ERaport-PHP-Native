<?php
include '../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['key'])) {
    $search = $_POST['key'];
    $query = "SELECT * FROM guru WHERE nama LIKE '$search%' LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="walas-result">' .  $row['nama'] . "(" . $row['nip'] . ")" . "</div>";
        }
    } else {
        echo '<div class="walas-no-result">Tidak ada hasil pencarian</div>';
    }
} else {
    header("location:./");
}
