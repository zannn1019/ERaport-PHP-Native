<?php
include '../../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:./../../");
    session_destroy();
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_role = $role;
$edit = $_GET['edit'];

$id = $_GET['id'];

if ($edit == "mapel") {
    $mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mapel WHERE id_mapel = '$id'"));
} elseif ($edit == "kelas") {
    $kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas LEFT JOIN guru ON kelas.wali_kelas = guru.id_guru INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE id_kelas = '$id'"));
} elseif ($edit == "ekskul") {
    $ekskul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ekskul INNER JOIN guru ON ekskul.id_guru = guru.id_guru WHERE id_ekskul = '$id'"));
}

if ($role == 1) {
    $user = mysqli_query($koneksi, "SELECT * FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE username = '$username'");
} elseif ($role == 2) {
    $user = mysqli_query($koneksi, "SELECT * FROM guru INNER JOIN role ON guru.id_role = role.id_role WHERE guru.nama = '$username'");
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
    <link rel="stylesheet" href="../../asset/CSS/dashboard.css" type="text/css" />
    <link rel="stylesheet" href="../../asset/FontAwesome/css/all.min.css">
    <link rel="stylesheet" href="../../asset/boxicons-2.1.4/css/boxicons.css">
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <div class="link">
            <p class="logo"><span>E</span>-Raport</p>
            <a href="../../dashboard/" class="icon-a "><i class="fa fa-dashboard icons">&nbsp;&nbsp;</i><b class="link_nama">Dashboard</b></a>
            <a class="icon-a dropdown-btn <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="../users/siswa/" class="d-list">Siswa</a></li>
                    <li><a href="../users/guru/" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?> <?= $edit == "mapel" ? 'active' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama ">Mapel</b></a>
            <a href="../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?> <?= $edit == "kelas" ? 'active' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="../ekskul/" class="icon-a <?= $role != 1 ?   'hidden'  : '' ?>"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a class="icon-a dropdown-btn <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../nilai/akademik/" class="d-list">Akademik</a></li>
                    <li><a href="../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <li><a href="../nilai/ekskul/" class="d-list">Ekskul</a></li>
                </ul>
            </div>
            <a href="../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Log Out</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Edit <?= $_GET['edit'] ?></span>
                </div>
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
                        <img alt="foto" src="../../asset/img/admin/<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
            </div>
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box">
                        <div class="tb-tittle">
                            <span></span>
                            <div class="files">
                                <a href="export.php">Export</a>
                                <a href="">Import</a>
                            </div>
                            <h1>Edit <?= $_GET['edit'] ?></h1>
                            <span></span>
                        </div>
                        <?php
                        switch ($_GET['edit']) {
                            case 'mapel': ?>
                                <div class="form-edit">
                                    <form action="aksi_edit.php" method="post">
                                        <div class="input-edit">
                                            <input type="hidden" name="id_mapel" value="<?= $mapel['id_mapel'] ?>">
                                            <span>Nama Mapel</span>
                                            <span>:</span>
                                            <input type="text" value="<?= $mapel['mapel'] ?>" name="mapel">
                                            <span>Jenis Mapel</span>
                                            <span>:</span>
                                            <select name="jenis-mapel" id="">
                                                <option value="1" <?= "umum" == $mapel['jenis'] ? 'selected' : '' ?>>Umum
                                                </option>
                                                <option value="2" <?= "kejuruan" == $mapel['jenis'] ? 'selected' : '' ?>>
                                                    Kejuruan</option>
                                            </select>
                                        </div>
                                        <br>
                                        <input type="submit" value="Edit Mapel" name="edit-mapel" class="submit-btn">
                                    </form>
                                </div>
                            <?php break;
                            case 'kelas': ?>
                                <div class="form-edit">
                                    <form action="aksi_edit.php" method="post">
                                        <div class="input-edit">
                                            <input type="hidden" name="id_kelas" value="<?= $kelas['id_kelas'] ?>">
                                            <span>Tingkat</span>
                                            <span>:</span>
                                            <select name="tingkat" id="">
                                                <option value="1" <?= "10" == $kelas['tingkatan'] ? 'selected' : '' ?>>10
                                                </option>
                                                <option value="2" <?= "11" == $kelas['tingkatan'] ? 'selected' : '' ?>>11
                                                </option>
                                                <option value="3" <?= "12" == $kelas['tingkatan'] ? 'selected' : '' ?>>12
                                                </option>
                                            </select>
                                            <span>Jurusan</span>
                                            <span>:</span>
                                            <select name="jurusan" id="">
                                                <?php
                                                $jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan");
                                                foreach ($jurusan as $j) { ?>
                                                    <option value="<?= $j['id_jurusan'] ?>" <?= $kelas['id_jurusan'] == $j['id_jurusan'] ? 'selected' : '' ?>>
                                                        <?= $j['jurusan'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span>Nama kelas</span>
                                            <span>:</span>
                                            <input type="text" value="<?= $kelas['nama_kelas'] ?>" name="kelas">
                                            <span>Wali Kelas</span>
                                            <span>:</span>
                                            <input type="text" id="wali_kelas" name="wali_kelas" value="<?= $kelas['nama'] ?? '' ?>" required>
                                            <span></span>
                                            <span></span>
                                            <div class="walas">
                                                <div id="walas-result"></div>
                                            </div>
                                        </div>
                                        <br>
                                        <input type="submit" value="Edit kelas" name="edit-kelas" class="submit-btn">
                                    </form>
                                </div>
                            <?php break;
                            case "ekskul": ?>
                                <div class="form-edit">
                                    <form action="aksi_edit.php" method="post">
                                        <div class="input-edit">
                                            <input type="hidden" value="<?= $ekskul['id_ekskul'] ?>" name="id_ekskul">
                                            <span>Nama Ekskul</span>
                                            <span>:</span>
                                            <input type="text" name="nama_ekskul" placeholder="Nama Ekskul" value="<?= $ekskul['nama_ekskul'] ?>" required>
                                            <span>Nama Pembina</span>
                                            <span>:</span>
                                            <input type="search" id="pembina" name="pembina" value="<?= $ekskul['nama'] . "(" . $ekskul['nip'] . ")" ?>">
                                            <span></span>
                                            <span></span>
                                            <div class="walas">
                                                <div id="walas-result"></div>
                                            </div>
                                        </div>
                                        <input type="submit" value="edit-ekskul" class="submit-btn" name="edit-ekskul">
                                    </form>
                                </div>
                        <?php
                            default:
                                break;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(document).on("click", function(event) {
            if (!$(event.target).is("#files, .files,.bx-folder-open")) {
                $(".files").css("display", "none");
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.walas-result', function() {
                var result = $(this).text();
                $('#wali_kelas').val(result);
                $('#pembina').val(result);
                $('#walas-result').css('display', 'none');
            });
            $('#wali_kelas').keyup(function() {
                var keyword = $(this).val();
                $('#walas_result').css('display', 'inline');
                if (keyword !== '') {
                    $.ajax({
                        url: 'walas.php',
                        type: 'GET',
                        data: {
                            key: keyword
                        },
                        success: function(response) {
                            $('#walas-result').html(response);
                        }
                    });
                }
                if (keyword === '') {
                    $('#walas-result').css('display', 'none');
                } else {
                    $('#walas-result').css('display', 'inline');
                }
            });
            $('#pembina').keyup(function() {
                var keyword = $(this).val();
                $('#walas_result').css('display', 'inline');
                if (keyword !== '') {
                    $.ajax({
                        url: 'get_pembina.php',
                        type: 'POST',
                        data: {
                            key: keyword
                        },
                        success: function(response) {
                            $('#walas-result').html(response);
                        }
                    });
                }
                if (keyword === '') {
                    $('#walas-result').css('display', 'none');
                } else {
                    $('#walas-result').css('display', 'inline');
                }
            });
            $("#files").click(function() {
                $(".files").css("display", "flex");
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