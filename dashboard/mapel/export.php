<?php
include '../../koneksi.php';

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
$query_page = "SELECT * FROM mapel ORDER BY id_mapel DESC ";
$result_page = mysqli_query($koneksi, $query_page);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Mapel.xls");
header("Pragma: no-cache");
header("Expires:0");
?>

<body>
    <table border='1'>
        <tr style="text-align: center;">
            <td colspan="8" style="text-align: center;">Data Seluruh Mapel</td>
        </tr>
        <tr style='height:30px;vertical-align:middle;text-align:center;font-size:16px'>
            <th>No</th>
            <th colspan="4">Nama Mapel</th>
            <th>Jumlah Guru</th>
        </tr>
        <?php foreach ($result_page as $m) {
        $id_mapel = $m['id_mapel'];
         $jumlah = mysqli_query($koneksi, "SELECT count(id_mapel) as jumlah FROM guru WHERE id_mapel = '$id_mapel'");
         $j_mapel = mysqli_fetch_array($jumlah);
                        ?>
            <tr>
                <td> <?= $no++ ?></td>
                 <td colspan="4"><?= $m['mapel'] ?></td>
                 <td><?= $j_mapel['jumlah'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>