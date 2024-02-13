<?php
include '../../../koneksi.php';
require_once '../../../asset/phpexcel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import-siswa'])) {
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
    $default_foto = "../../../asset/img/user.png";
    $jumlah_data = 0;
    $data_gagal = 0;
    if ($highestColumn == "I") {
        for ($row = 2; $row <= $highestRow; $row++) {
            awal_siswa:
            $data = array();
            for ($column = 'A'; $column <= $highestColumn; $column++) {
                $data[] = $sheet->getCell($column . $row)->getValue();
            }
            $pisah_kelas = explode(' ', $data[3]);
            $tingkat = $pisah_kelas[0];
            $jurusan = $pisah_kelas[1];
            $nama_kelas = $pisah_kelas[2];
            $query = mysqli_query($koneksi, "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE kelas.tingkatan = '$tingkat' AND nama_kelas = '$nama_kelas' AND jurusan.jurusan = '$jurusan'");
            $kelas = mysqli_fetch_assoc($query);
            @$id_kelas = $kelas['id_kelas'];
            $password = randompass();
            $tahun_ajar = date("Y") . "-" . date("Y", strtotime("+1 year"));
            $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$data[1]'");
            if ($id_kelas == null) {
                $jurusan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM jurusan WHERE jurusan LIKE '%$jurusan%'"));
                @$id_jurusan = $jurusan['id_jurusan'];
                $tambah_kelas = mysqli_query($koneksi, "INSERT INTO kelas VALUES(null,'$tingkat','$id_jurusan','$nama_kelas',null)");
                if ($tambah_kelas) {
                    goto awal_siswa;
                }
            }
            $query = mysqli_query($koneksi, "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE kelas.tingkatan = '$tingkat' AND nama_kelas = '$nama_kelas' AND jurusan.jurusan = '$jurusan'");
            $kelas = mysqli_fetch_assoc($query);
            @$id_kelas = $kelas['id_kelas'];
            if (mysqli_num_rows($cek_nis) == 0) {
                $query = mysqli_query($koneksi, "INSERT INTO siswa VALUES(NULL,'$data[0]','$data[1]','$default_foto','$data[2]','$password','$id_kelas','3','$data[4]','$data[5]',CURRENT_TIMESTAMP,'$data[6]','$data[7]','$data[8]','1','$tahun_ajar')");
                // $query_jumlah = mysqli_affected_rows($koneksi);
                // $jumlah_data += $query_jumlah;
                // if ($row == $highestRow - 1) {
                //     $jumlah_data += 1;
                //     if ($query) {
                //         echo "<script> alert('$jumlah_data data berhasil ditambahkan!'); </script>";
                //         echo "<script> document.location.href='../siswa/' </script>";
                //     } else {
                //         echo "<script> alert('Data gagal ditambahkan!'); </script>";
                //         echo "<script> document.location.href='../siswa/' </script>";
                //     }
                // }
            }
            echo "<script> alert('Data berhasil ditambahkan, silahkan pastikan kembali jika ada data yang kurang!'); </script>";
            echo "<script> document.location.href='../siswa/' </script>";
        }
    } else {
        echo "<script> alert('File excel tidak sesuai format, mohon untuk periksa file excel kembali'); </script>";
        echo "<script> document.location.href='../siswa/' </script>";
    }
} elseif (isset($_POST['import-guru'])) {
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
    $default_foto = "../../../asset/img/user.png";
    $jumlah_data = 0;
    if ($highestColumn == "H") {
        for ($row = 2; $row <= $highestRow; $row++) {
            awal_guru:
            $data = array();
            for ($column = 'A'; $column <= $highestColumn; $column++) {
                $data[] = $sheet->getCell($column . $row)->getValue();
            }
            $query = mysqli_query($koneksi, "SELECT * FROM mapel WHERE mapel LIKE '%$data[3]%'");
            $mapel = mysqli_fetch_assoc($query);
            @$id_mapel = $mapel['id_mapel'];
            $password = randompass();
            if ($id_mapel == null) {
                echo "<script> alert('Mata Pelajaran dengan nama $data[3] belum ada, mohon untuk menambahkan mata pelajaran terlebih dahulu'); </script>";
                echo "<script> document.location.href='../../mapel/' </script>";
            }
            $cek_nip = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$data[1]'");
            if (mysqli_num_rows($cek_nip) == 0) {
                $query = mysqli_query($koneksi, "INSERT INTO guru VALUES(NULL,'$data[0]','$default_foto','$data[1]','$data[2]','$password','$id_mapel','2','$data[4]','$data[5]',CURRENT_TIMESTAMP,'$data[6]','$data[7]')");
                echo "<script> alert('Data berhasil ditambahkan, silahkan pastikan kembali jika ada data yang kurang!'); </script>";
                echo "<script> document.location.href='../guru/' </script>";
            }
            // $query_jumlah = mysqli_affected_rows($koneksi);
            // $jumlah_data += $query_jumlah;
            // if ($row == $highestRow - 1) {
            //     if ($query) {
            //         $jumlah_data += 1;
            //         echo "<script> alert('$jumlah_data data berhasil ditambahkan!'); </script>";
            //         echo "<script> document.location.href='../guru/' </script>";
            //     } else {
            //         echo "<script> alert('Data gagal ditambahkan!'); </script>";
            //         echo "<script> document.location.href='../guru/' </script>";
            //     }
            // }
        }
    } else {
        echo "<script> alert('File excel tidak sesuai format, mohon untuk periksa file excel kembali'); </script>";
        echo "<script> document.location.href='../guru/' </script>";
    }
} else {
    echo "<script> document.location.href='../../' </script>";
}
