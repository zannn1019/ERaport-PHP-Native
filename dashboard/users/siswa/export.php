<?php
include '../../../koneksi.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
$no = 1;
$query_page = "SELECT *  FROM siswa  INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan ORDER BY id_siswa";
$result_page = mysqli_query($koneksi, $query_page);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Siswa.xls");
header("Pragma: no-cache");
header("Expires:0");
?>

<body>
    <table border='1'>
        <tr style="text-align: center;">
            <td colspan="8" style="text-align: center;">Data Seluruh Siswa</td>
        </tr>
        <tr style='height:30px;vertical-align:middle;text-align:center;font-size:16px'>
            <th>No</th>
            <th>NIS</th>
            <th colspan="5">Nama</th>
            <th>Kelas</th>
        </tr>
        <?php
        foreach ($result_page as $s) {
        ?>
            <tr>
                <td> <?= $no++ ?></td>
                <td><?= $s['nis'] ?></td>
                <td colspan="5"><?= $s['nama'] ?></td>
                <td><?= $s['tingkatan'] . " " .  $s['jurusan'] . " " .  $s['nama_kelas'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>