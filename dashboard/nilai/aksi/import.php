<?php
include '../../../koneksi.php';
require_once '../../../asset/phpexcel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function hitungPredikat($nilai)
{
    $kategorinilai = [
        ['A', 90, 100],
        ['B', 80, 89],
        ['C', 70, 79],
        ['D', 60, 69],
        ['E', 0, 59]
    ];
    $predikat = '';
    foreach ($kategorinilai as $grade) {
        if ($nilai >= $grade[1] && $nilai <= $grade[2]) {
            $predikat = $grade[0];
        }
    }
    return $predikat;
}

if (isset($_POST['import-akademik'])) {
    $id_guru = $_POST['id_guru'];
    $nama_file = $_FILES['file']['name'];
    $tipeFile = pathinfo($nama_file, PATHINFO_EXTENSION);
    if ($tipeFile != 'xlsx' && $tipeFile != 'xls') {
        die('Error: Tolong pilih file excel');
    }
    $tmp_file = $_FILES['file']['tmp_name'];
    $reader = IOFactory::createReaderForFile($tmp_file);
    $spreadsheet = $reader->load($tmp_file);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $jumlah_data = 0;

    for ($row = 2; $row <= $highestRow; $row++) {
        $data = array();
        for ($column = 'A'; $column <= $highestColumn; $column++) {
            $data[] = $sheet->getCell($column . $row)->getValue();
        }
        $nis = $data[0];
        $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_siswa FROM siswa WHERE nis = '$nis'"));
        $id_siswa = $siswa['id_siswa'];
        $nilai_p = ($data[2] + $data[3] + $data[4]) / 3;
        $nilai_k = $data[6];
        $grade_p = hitungPredikat($nilai_p);
        $grade_k = hitungPredikat($nilai_k);
        try {
            $cek = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa = $id_siswa AND id_semester = $data[1]");
            if (!mysqli_num_rows($cek) > 0) {
                $query = mysqli_query($koneksi, "INSERT INTO nilai VALUES(NULL,'$id_guru','$id_siswa','$data[1]','$data[2]','$data[3]','$data[4]','$nilai_p','$grade_p','$data[5]','$data[6]','$grade_k','$data[7]',NULL,NULL)");
                if ($row == $highestRow - 1) {
                    if ($query) {
                        echo "<script> alert('Semua data berhasil ditambahkan, data duplikat diabaikan!'); </script>";
                        echo "<script> document.location.href='../akademik/' </script>";
                    } else {
                        echo "<script> alert('Data gagal ditambahkan!'); </script>";
                        echo "<script> document.location.href='../akademik/' </script>";
                    }
                }
            } else {
                echo "<script> alert('Semua data berhasil ditambahkan, data duplikat diabaikan!'); </script>";
                echo "<script> document.location.href='../akademik/' </script>";
            }
        } catch (Exception $e) {
            echo "<script> alert('Data gagal ditambahkan, cobalah untuk cek apakah data tersebut sudah ada atau tidak!'); </script>";
            echo "<script> document.location.href='../akademik/' </script>";
        }
    }
} elseif (isset($_POST['import-absensi'])) {
    $nama_file = $_FILES['file']['name'];
    $tipeFile = pathinfo($nama_file, PATHINFO_EXTENSION);
    if ($tipeFile != 'xlsx' && $tipeFile != 'xls') {
        die('Error: Tolong pilih file excel');
    }
    $tmp_file = $_FILES['file']['tmp_name'];
    $reader = IOFactory::createReaderForFile($tmp_file);
    $spreadsheet = $reader->load($tmp_file);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $jumlah_data = 0;
    $id_guru = $_POST['id_guru'];
    for ($row = 2; $row <= $highestRow; $row++) {
        $data = array();
        for ($column = 'A'; $column <= $highestColumn; $column++) {
            $data[] = $sheet->getCell($column . $row)->getValue();
        }
        $nis = $data[0];
        $siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'"));
        $id_siswa = $siswa['id_siswa'];
        try {
            $cek = mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_siswa = $id_siswa AND id_semester = $data[4]");
            if (!mysqli_num_rows($cek) > 0) {
                $query = mysqli_query($koneksi, "INSERT INTO absensi VALUES(NULL,'$data[1]','$data[2]','$data[3]','$id_siswa','$data[4]','$id_guru')");
                if ($row == $highestRow - 1) {
                    if ($query) {
                        echo "<script> alert('Semua data berhasil ditambahkan, data duplikat diabaikan!'); </script>";
                        echo "<script> document.location.href='../kehadiran/' </script>";
                    } else {
                        echo "<script> alert('Data gagal ditambahkan!'); </script>";
                        echo "<script> document.location.href='../kehadiran/' </script>";
                    }
                }
            } else {
                echo "<script> alert('Semua data berhasil ditambahkan, data duplikat diabaikan!'); </script>";
                echo "<script> document.location.href='../kehadiran/' </script>";
            }
        } catch (Exception $e) {
        }
    }
} else {
    echo "<script> document.location.href='../../' </script>";
}
