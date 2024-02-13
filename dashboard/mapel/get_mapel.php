<?php
include '../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM mapel WHERE mapel LIKE '$search%' LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $mapel) {
            echo "<a href='../aksi/edit.php?edit=mapel&id=" . $mapel['id_mapel']. "'><div class='search-items'><b>" . $mapel['mapel'] . "</b></div></a>";
        }
    }
} else {
    header("location:./");
}