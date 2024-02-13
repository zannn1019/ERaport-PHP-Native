<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1, 2])) {
    header("location:../../");
    session_destroy();
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_guru = $_SESSION['id_guru'] ?? null;
$id_role = $role;
if ($role == 1) {
    $user = mysqli_query($koneksi, "SELECT * FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE username = '$username'");
} elseif ($role == 2) {
    $user = mysqli_query($koneksi, "SELECT * FROM guru INNER JOIN role ON guru.id_role = role.id_role WHERE guru.nama = '$username'");
    $cek_wakel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas = '$id_guru'"));
    $cek_ekskul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ekskul WHERE id_guru = '$id_guru'"));
    if ($cek_wakel == null) {
        header("location:../../../");
    }
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}
$data_user = mysqli_fetch_assoc($user);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $title = basename(__DIR__) ?>
    <title><?= ucwords($title) ?></title>
    <link rel="stylesheet" href="../../../asset/CSS/dashboard.css" type="text/css" />
    <link rel="stylesheet" href="../../../asset/FontAwesome/css/all.min.css">
    <link rel="stylesheet" href="../../../asset/boxicons-2.1.4/css/boxicons.css">
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <div class="link">
            <p class="logo"><span>E</span>-Raport</p>
            <a href="../../../dashboard/" class="icon-a "><i class="fa fa-dashboard icons">&nbsp;&nbsp;</i><b class="link_nama">Dashboard</b></a>
            <a class="icon-a dropdown-btn <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="../../users/siswa/" class="d-list">Siswa</a></li>
                    <li><a href="../../users/guru/" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="../../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="../../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="../../ekskul/" class="icon-a <?= $role != 1 ?   'hidden'  : '' ?>"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a class="icon-a dropdown-btn active <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons icons">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../akademik/" class="d-list">Akademik</a></li>
                    <li><a href="./" class="d-list">Kehadiran</a></li>
                    <?php if (isset($cek_ekskul) || $role == 1) { ?>
                        <li><a href="../ekskul/" class="d-list">Ekskul</a></li>
                    <?php } ?>
                </ul>
            </div>
            <a href="../../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Keluar</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Data Kehadiran</span>
                </div>
                <?php
                $id_jurusan = $_GET['jurusan'] ?? null;
                $id_kelas = $_GET['kelas'] ?? null;
                $id_semester = $_GET['semester'] ?? null;
                if ($id_kelas != null && $id_semester != null) { ?>
                    <div class="search">
                        <input type="search" name="" id="search-input" placeholder="Cari data <?= $title ?>">
                        <div class="search-list" id="search-list">
                        </div>
                    </div>
                <?php } ?>
                <div class="col-div-6">
                    <div class="profile">
                        <label class="switch" for="checkbox">
                            <div class="theme-icon">
                                <i class='bx bx-moon'></i>
                                <i class='bx bx-sun'></i>
                            </div>
                            <input type="checkbox" id="checkbox" />
                            <div class="slider round"></div>
                        </label>
                        <p><?= $data_user['nama'] ?><span><?= $data_user['role'] ?></span></p>
                        <img alt="foto" src="../../../asset/img/admin/<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
            </div>
            <div class="nilai-option">
                <?php if ($id_guru == null) { ?>
                    <form action="" method="get" id="form-input">
                        <div class="jurusan">
                            <p>Jurusan</p>
                            <select name="jurusan" id="select-jurusan" required>
                                <option value="">Pilih jurusan</option>
                                <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM jurusan");
                                foreach ($query as $jurusan) { ?>
                                    <option value="<?= $jurusan['id_jurusan'] ?>" <?= @$_GET['jurusan'] == $jurusan['id_jurusan'] ? 'selected' : '' ?>>
                                        <?= $jurusan['jurusan'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="kelas">
                            <p>Kelas</p>
                            <select name="kelas" id="kelas" disabled required>
                                <option value="">Pilih kelas</option>
                            </select>
                        </div>
                        <div class="semester">
                            <p>Semester</p>
                            <select name="semester" id="" required>
                                <option value="">Pilih semester</option>
                                <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM semester");
                                foreach ($query as $semester) { ?>
                                    <option value="<?= $semester['id_semester'] ?>" <?= @$_GET['semester'] == $semester['id_semester'] ? 'selected' : '' ?>>
                                        <?= $semester['semester'] ?></option>
                                <?php } ?>
                            </select>
                            <input type="submit" value="Cari">
                        </div>
                    </form>
                <?php } ?>
                <?php
                $id_jurusan = $_GET['jurusan'] ?? null;
                $id_kelas = $_GET['kelas'] ?? null;
                $id_semester = $_GET['semester'] ?? null;
                $limit = 10;
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                } else {
                    $page = 1;
                }
                $offset = ($page - 1) * $limit;
                $no = 1;
                if ($id_guru == null) {
                    $query_total = "SELECT COUNT(*) AS total_data FROM absensi INNER JOIN siswa ON absensi.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas WHERE siswa.id_kelas = '$id_kelas' AND absensi.id_semester = '$id_semester'";
                    $query_page = "SELECT * FROM absensi INNER JOIN siswa ON absensi.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE siswa.id_kelas = '$id_kelas' AND absensi.id_semester = '$id_semester' ORDER BY absensi.id_absen DESC LIMIT $limit OFFSET $offset";
                } else {
                    $query_total = "SELECT COUNT(*) AS total_data FROM absensi WHERE absensi.id_guru = '$id_guru'";
                    $query_page = "SELECT * FROM absensi INNER JOIN siswa ON absensi.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE absensi.id_guru = '$id_guru' ORDER BY absensi.id_absen DESC LIMIT $limit OFFSET $offset";
                }
                $result_total = mysqli_query($koneksi, $query_total);
                $row_total = mysqli_fetch_assoc($result_total);
                $total_data = $row_total['total_data'];
                $total_page = ceil($total_data / $limit);
                $result_page = mysqli_query($koneksi, $query_page);
                $next = $page >= $total_page ? 1 : $page + 1;
                $prev = $page <= 1 ? 1 : $page - 1;
                $jumlah_data = mysqli_fetch_all($result_page, MYSQLI_ASSOC);
                if (count($jumlah_data) > 0) { ?>
                    <?php if ($id_guru == null) { ?>
                        <div class="pagination">
                            <div class="pagination-link">
                                <a href="?jurusan=<?= $id_jurusan ?>&kelas=<?= $id_kelas ?>&semester=<?= $id_semester ?>&page=<?= $prev ?>"><i class='bx bx-skip-previous'></i></a>
                                <div class="pg-num">
                                    <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                                        <?php if ($i == $page) { ?>
                                            <a href="?page=<?= $i ?>" class="pagi-active"><?= $i ?></a>
                                        <?php } else { ?>
                                            <a href="?jurusan=<?= $_GET['jurusan'] ?>&kelas=<?= $_GET['kelas'] ?>&page=<?= $i ?>"><?= $i ?></a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <a href="?jurusan=<?= $id_jurusan ?>&kelas=<?= $id_kelas ?>&semester=<?= $id_semester ?>&page=<?= $next ?>"><i class='bx bx-skip-next'></i></a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="pagination">
                            <div class="pagination-link">
                                <a href="?page=<?= $prev ?>"><i class='bx bx-skip-previous'></i></a>
                                <div class="pg-num">
                                    <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                                        <?php if ($i == $page) { ?>
                                            <a href="?page=<?= $i ?>" class="pagi-active"><?= $i ?></a>
                                        <?php } else { ?>
                                            <a href="?page=<?= $i ?>"><?= $i ?></a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <a href="?page=<?= $next ?>"><i class='bx bx-skip-next'></i></a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-div-8 ">
                        <div class="box-8 mb-5">
                            <div class="content-box">
                                <div class="tb-tittle">
                                    <button class="export-btn" id="files"><i class='bx bx-folder-open'></i></button>
                                    <div class="files">
                                        <a href="export.php?kelas=<?= $id_kelas ?>&semester=<?= $id_semester ?>">Export</a>
                                        <button class="import-btn" style="<?= $id_guru == null ? 'visibility:hidden;' : '' ?>">Import</button>
                                    </div>
                                    <h1>Data Kehadiran</h1>
                                    <button class="add-btn" <?= $_SESSION['role'] == 2 ? '' : 'disabled' ?>><i class='bx bx-plus'></i></button>
                                </div>
                                <table>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alfa</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                    <?php
                                    $per_page = 1 * $offset;
                                    $no = $per_page + 1;
                                    foreach ($result_page as $s) {
                                    ?>
                                        <tr>
                                            <td> <?= $no++ ?></td>
                                            <td><?= $s['nis'] ?></td>
                                            <td><?= $s['nama'] ?></td>
                                            <td><?= $s['tingkatan'] . ' ' . $s['jurusan'] . ' ' . $s['nama_kelas'] ?></td>
                                            <td><?= $s['izin'] ?></td>
                                            <td><?= $s['sakit'] ?></td>
                                            <td><?= $s['alfa'] ?></td>
                                            <td>
                                                <div class="aksi-field">
                                                    <a href="../detail/?detail=absen&id=<?= $s['id_absen'] ?>" class="detail-btn"><i class='bx bxs-user-detail'></i></a>
                                                    <?php if ($_SESSION['role'] == 2) { ?>
                                                        <a href="../aksi/edit.php?nilai=absensi&id=<?= $s['id_absen'] ?>" class="edit-btn"><i class='bx bxs-edit'></i></a>
                                                    <?php } ?>
                                                    <a href="../aksi/delete.php?nilai=absensi&id=<?= $s['id_absen'] ?>" class="delete-btn" onclick="return confirm('Anda yakin untuk menghapus?')"><i class='bx bxs-trash'></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php if ($id_kelas != null && $id_semester != null) { ?>
                        <br>
                        <div class="col-div-8 ">
                            <div class="box-8 mb-5">
                                <div class="content-box">
                                    <div class="tb-tittle">
                                        <button class="export-btn" id="files"><i class='bx bx-folder-open'></i></button>
                                        <div class="files">
                                            <a href="export.php">Export</a>
                                            <button class="import-btn" style="<?= $id_guru == null ? 'visibility:hidden;' : '' ?>">Import</button>
                                        </div>
                                        <h1>Data Akademik</h1>
                                        <button class="add-btn" <?= $_SESSION['role'] == 2 ? '' : 'disabled' ?>><i class='bx bx-plus'></i></button>
                                    </div>
                                    <h5>Tidak ada data</h5>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($id_guru != null) { ?>
                        <br>
                        <div class="col-div-8 ">
                            <div class="box-8 mb-5">
                                <div class="content-box">
                                    <div class="tb-tittle">
                                        <button class="export-btn" id="files"><i class='bx bx-folder-open'></i></button>
                                        <div class="files">
                                            <a href="export.php">Export</a>
                                            <button class="import-btn" style="<?= $id_guru == null ? 'visibility:hidden;' : '' ?>">Import</button>
                                        </div>
                                        <h1>Data Kehadiran</h1>
                                        <button class="add-btn" <?= $_SESSION['role'] == 2 ? '' : 'disabled' ?>><i class='bx bx-plus'></i></button>
                                    </div>
                                    <h5>Belum ada data yang ditambahkan</h5>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="add-popup">
        <div class="popup-close">
            <span>Klik dimana saja untuk menutup</span>
        </div>
        <div class="parent-tambah">
            <form action="../aksi/tambah_nilai.php" method="post" enctype="multipart/form-data" class="form-user">
                <div class="form-tambah">
                    <input type="hidden" name="id_guru" id="id_guru" value="<?= $id_guru ?>">
                    <span>Nama Siswa</span>
                    <span>:</span>
                    <input type="text" id="search-siswa" name="nama_siswa" placeholder="Masukkan Nama/NIS siswa" required>
                    <div class="search-field" id="search-field">
                    </div>
                    <span>Semester</span>
                    <span>:</span>
                    <select name="semester" id="" required>
                        <option value="">Pilih semester</option>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM semester");
                        foreach ($query as $semester) { ?>
                            <option value="<?= $semester['id_semester'] ?>"><?= $semester['semester'] ?></option>
                        <?php } ?>
                    </select>
                    <span>Izin</span>
                    <span>:</span>
                    <input type="number" name="izin" id="" required>
                    <span>Sakit</span>
                    <span>:</span>
                    <input type="number" name="sakit" id="" required>
                    <span>Tanpa Keterangan</span>
                    <span>:</span>
                    <input type="number" name="alfa" id="" required>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <input type="submit" value="Tambah Kehadiran" class="submit-btn" name="tambah-absensi">
                </div>
            </form>
        </div>
    </div>
    <div class="import-popup">
        <div class="import-close"><span>Klik dimana saja untuk menutup</span></div>
        <form action="../aksi/import.php" method="post" class="import-form" enctype="multipart/form-data">
            <a href="../../../asset/excel/example_import_absen.xlsx">Donwload Templates Excel</a>
            <input type="hidden" name="id_guru" value="<?= $id_guru ?>">
            <div class="container">
                <div class="header">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24006 10.1959M12 12V21M12 12L15 15M12 12L9 15" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" id="import-ico"></path>
                        </g>
                    </svg>
                    <p>Browse File to upload!</p>
                </div>
                <label for="file" class="footer">
                    <p id="nama-file">Not selected file</p>
                </label>
                <input id="import-file" name="file" type="file" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
            </div>
            <input type="submit" value="Import data" name="import-absensi">
        </form>
    </div>
    <script src="../../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(window).on('load', function() {
            const urlParams = new URLSearchParams(window.location.search)
            var id_kelas = urlParams.get('kelas')
            var id_jurusan = $("#select-jurusan").val();
            $.ajax({
                url: 'get_kelas.php',
                type: 'POST',
                data: {
                    id_jurusan: id_jurusan,
                    id_kelas: id_kelas
                },
                dataType: 'html',
                success: function(result) {
                    if (id_jurusan == '') {
                        $('#kelas').html(result).prop('disabled', true);
                    } else {
                        $('#kelas').html(result).prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
        $(document).on("click", function(event) {
            if (!$(event.target).is("#files, .files,.bx-folder-open")) {
                $(".files").css("display", "none");
            }
        });

        function searchPerkelas(keyword) {
            const urlParams = new URLSearchParams(window.location.search)
            var id_kelas = urlParams.get('kelas')
            var semester = urlParams.get('semester')
            $.ajax({
                url: 'get_siswa_perkelas.php',
                type: 'POST',
                data: {
                    search: keyword,
                    id: id_kelas,
                    semester: semester
                },
                dataType: 'html',
                success: function(result) {
                    $("#search-list").html(result)
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.')
                }
            })
        }

        function search(keyword) {
            var id_guru = $("#id_guru").val();
            $.ajax({
                url: 'get_siswa.php',
                type: 'POST',
                data: {
                    search: keyword,
                    id_guru: id_guru
                },
                dataType: 'html',
                success: function(result) {
                    $("#search-field").html(result)
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            })
        }
        $(document).ready(function() {
            $("#search-input").on('keyup', function() {
                $('#search-list').css("display", 'inline-block')
                searchPerkelas($(this).val())
            })
            $("#files").click(function() {
                $(".files").css("display", "flex");
            });
            $(".add-btn").click(function() {
                $(".add-popup").css({
                    'bottom': '',
                    'top': '0',
                    'left': '',
                    'right': '0'
                });
                $(".add-popup").toggle('display');
                $(".add-popup").css('display', 'flex');
            });
            $(".popup-close").click(function() {
                $(".add-popup").css({
                    'bottom': '0',
                    'top': '',
                    'left': '0',
                    'right': ''
                });
                $(".add-popup").toggle('display');
            });
            $("#search-siswa").on('keyup', function() {
                if ($("#search-siswa").val() == "") {
                    $('#search-field').css("display", 'none')
                } else {
                    $('#search-field').css("display", 'inline-block')
                }
                search($(this).val())
            })
            $(document).on('click', '.search-pilihan', function() {
                var result = $(this).text();
                $('#search-siswa').val(result);
                $('#search-field').css("display", 'none')
            });

            if (localStorage.getItem("mode") == "dark") {
                $("body").addClass("dark-mode");
                $("#checkbox").prop("checked", true);
            }
            $("#checkbox").change(function() {
                if ($(this).is(":checked")) {
                    $("body").addClass("dark-mode");
                    localStorage.setItem("mode", "dark");
                } else {
                    $("body").removeClass("dark-mode");
                    localStorage.setItem("mode", "light");
                }
            });
            $("#import-file").change(function() {
                const file = this.files[0]['name'];

                if (file) {
                    $("#nama-file").text(file);
                }
            })
            $(".import-btn").click(function() {
                $(".import-popup").toggle("display")
                $(".import-popup").css("display", 'flex')
            });
            $(".import-close").click(function() {
                $(".import-popup").toggle("display")
            });
            $('#select-jurusan').on('change', function() {
                var id_jurusan = $(this).val();
                $.ajax({
                    url: 'get_kelas.php',
                    type: 'POST',
                    data: {
                        id_jurusan: id_jurusan
                    },
                    dataType: 'html',
                    success: function(result) {
                        if (id_jurusan == '') {
                            $('#kelas').html(result).prop('disabled', true);
                        } else {
                            $('#kelas').html(result).prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
            $(".content").click(function() {
                $("#mySidenav").css('width', '70px');
                $("#main").css('margin-left', '70px');
                $(".logo").css('visibility', 'hidden');
                $(".logo span").css('visibility', 'visible');
                $(".logo span").css('margin-left', '-10px');
                $(".icon-a").css('visibility', 'hidden');
                $(".icons").css('visibility', 'visible');
                $(".icons").css('margin-left', '-8px');
                $(".active").css("border", "none");
                $(".link_nama").css("display", "none");
                $(".dropdown").css("display", "none");
                $("#logout-txt").css("display", "none");
                $(".logout").css("left", "0");
                $(".drop-arrow").css("visibility", "hidden");
            });
            $(".dropdown-btn").click(function() {

                $(".dropdown").not($(this).data("target")).hide();
                $($(this).data("target")).toggle();

                $(this).find(".slash-kiri").toggleClass("rotate-kiri")
                $(this).find(".slash-kanan").toggleClass("rotate-kanan")
                $(".dropdown-btn").not($(this)).find(".slash-kiri").removeClass("rotate-kiri");
                $(".dropdown-btn").not($(this)).find(".slash-kanan").removeClass("rotate-kanan");
            })
            $("#mySidenav").click(function() {
                $("#mySidenav").css('width', '300px');
                $("#main").css('margin-left', '300px');
                $(".logo").css('visibility', 'visible');
                $(".icon-a").css('visibility', 'visible');
                $(".icons").css('visibility', 'visible');
                $(".active").css("border-left", "10px solid white");
                $(".link_nama").css("display", "inline");
                $("#logout-txt").css("display", "inline");
                $("#logout").css("padding-left", "0px");
                $(".logout").css("left", "60px");
                $(".drop-arrow").css("visibility", "visible");
            });
        });
    </script>
</body>


</html>