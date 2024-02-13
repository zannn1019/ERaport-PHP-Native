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
if ($role == 1) {
    $user = mysqli_query($koneksi, "SELECT * FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE username = '$username'");
} elseif ($role == 2) {
    $user = mysqli_query($koneksi, "SELECT * FROM guru INNER JOIN role ON guru.id_role = role.id_role WHERE guru.nama = '$username'");
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}
$data_user = mysqli_fetch_assoc($user);
$jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan");
$no = 1;
$id_role = $role;
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
            <a href="../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="./../ekskul/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?> "><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a href="../nilai/nilai siswa/" class="icon-a <?= $role != 3 ? 'hidden' : '' ?>"><i class="bx bxs-objects-vertical-bottom icons">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b></a>
            <a class="icon-a dropdown-btn <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>

            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../nilai/akademik/" class="d-list">Akademik</a></li>
                    <li><a href="../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <li><a href="../nilai/ekskul/" class="d-list">Ekskul</a></li>

                </ul>
            </div>
            <a href="../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?> active"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Log Out</b></a>
        </div>
    </div>
    <div class="content">

        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Pengaturan</span>
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
            <div class="col-div-8 ">
                <div class="box-8 mb-5">
                    <div class="content-box">
                        <div class="tb-tittle">
                            <span></span>
                            <h1 style="<?= $id_role == '1' ? '' : 'display: none;' ?>">Pengaturan Data ERaport</h1>
                            <span></span>
                        </div>
                        <h2>Pengaturan Sekolah</h2>
                        <?php
                        $info_sekolah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM informasi_sekolah"));
                        ?>
                        <form class="pengaturan-sekolah" id="form-sekolah" method="post">
                            <span>Nama Sekolah </span>
                            <span>:</span>
                            <input type="text" name="nama_sekolah" value="<?= $info_sekolah['nama_sekolah'] ?>" required>
                            <span>Kepala Sekolah </span>
                            <span>:</span>
                            <input type="text" name="kepsek" value="<?= $info_sekolah['kepala_sekolah'] ?>" required>
                            <span>NIP</span>
                            <span>:</span>
                            <input type="text" name="nip" value="<?= $info_sekolah['nip'] ?>" required>
                            <span>Alamat </span>
                            <span>:</span>
                            <textarea name="alamat_sekolah" id="" cols="30" rows="5" required><?= $info_sekolah['alamat_sekolah'] ?></textarea>
                            <span>Nomor Telepon </span>
                            <span>:</span>
                            <input type="text" name="no_sekolah" value="<?= $info_sekolah['notelp_sekolah'] ?>" required>
                            <span>Email </span>
                            <span>:</span>
                            <input type="gmail" name="email_sekolah" value="<?= $info_sekolah['email_sekolah'] ?>" required>
                            <span>Website </span>
                            <span>:</span>
                            <input type="text" name="website_sekolah" value="<?= $info_sekolah['website_sekolah'] ?>" required>
                            <span></span>
                            <span></span>
                            <input type="submit" value="Update">
                        </form>
                        <br>
                        <br>
                        <h3>Hapus Data</h3>
                        <i>Tindakan ini tidak dapat dibatalkan dan akan menghapus seluruh data secara
                            <b>permanen!</b></i>
                        <br>
                        <br>
                        <div class=" pengaturan">
                            <div class="card-setting">
                                <span>Hapus Data Siswa</span>
                                <button class="del-btn" data-data="siswa">Hapus</button>
                            </div>
                            <div class="card-setting">
                                <span>Hapus Data Guru</span>
                                <button class="del-btn" data-data="guru">Hapus</button>
                            </div>
                            <div class="card-setting">
                                <span>Hapus Data Kelas</span>
                                <button class="del-btn" data-data="kelas">Hapus</button>
                            </div>
                            <div class="card-setting">
                                <span>Hapus Data Mapel</span>
                                <button class="del-btn" data-data="mapel">Hapus</button>
                            </div>
                            <div class="card-setting">
                                <span>Hapus Data Nilai</span>
                                <button class="del-btn" data-data="nilai">Hapus</button>
                            </div>
                            <div class="card-setting">
                                <span>Hapus Data Absensi</span>
                                <button class="del-btn" data-data="absensi">Hapus</button>
                            </div>
                        </div>
                        <br>
                        <br>
                        <h3>Backup Data</h3>
                        <i>Tindakan ini dapat meng backup seluruh data ke dalam
                            <b>Excel</b></i>
                        <br>
                        <br>
                        <div class=" pengaturan">
                            <div class="card-setting">
                                <span>Backup Data Siswa</span>
                                <a href="../users/siswa/export.php" class="exp-btn">Export</a>
                            </div>
                            <div class="card-setting">
                                <span>Backup Data Guru</span>
                                <a href="../users/guru/export.php" class="exp-btn">Export</a>
                            </div>
                            <div class="card-setting">
                                <span>Backup Data Kelas</span>
                                <a href="../kelas/export.php" class="exp-btn">Export</a>
                            </div>
                            <div class="card-setting">
                                <span>Backup Data Mapel</span>
                                <a href="../mapel/export.php" class="exp-btn">Export</a>
                            </div>
                            <div class="card-setting">
                                <span>Backup Data Nilai</span>
                                <a href="../nilai/akademik/export.php" class="exp-btn">Export</a>
                            </div>
                            <div class="card-setting">
                                <span>Backup Data Absensi</span>
                                <a href="../nilai/kehadiran/export.php" class="exp-btn">Export</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="notif" id="notif">
        <i class="bx bx-check-circle" id="notif-icon"></i>
        <div class="text" id="text">
            <h3 id="notif-textatas">Pendaftaran Berhasil</h3>
            <p id="notif-textbawah">
                Terimakasih sudah mendaftarkan diri di OPENHOUSE SMK NEGERI
                2 CIMAHI
            </p>
        </div>
    </div>

    <script src="../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#form-sekolah").submit(function() {
                event.preventDefault();
                var data = $(this).serialize();
                $.ajax({
                    url: "update_sekolah.php",
                    method: "POST",
                    data: data,
                    success: function(response) {
                        if (response == "0") {
                            $("#notif-icon")
                                .removeClass("bx-check-circle")
                                .addClass("bx-x-circle");
                            $("#notif-icon").css("color", "crimson");
                            $("#notif").css("color", "crimson");
                            $("#notif").css("border", "3px solid crimson");
                            $("#text").css("border-left", " 2px dashed crimson");
                            $("#notif-textatas").css("color", "crimson");
                            $("#notif-textatas").html("Update gagal!");
                            $("#notif-textbawah").html(
                                "Data informasi sekolah gagal di update!"
                            );
                            $("#notif").toggle("display");
                            $("#notif").css("display", "flex");
                            let display = $("#notif").css("display");
                            if (display == "flex") {
                                setTimeout(() => {
                                    $("#notif").toggle("display");
                                }, 3000);
                            }
                        } else {
                            $("#notif-icon")
                                .removeClass("bx-x-circle")
                                .addClass("bx-check-circle");
                            $("#notif-icon").css("color", "green");
                            $("#notif").css("color", "green");
                            $("#notif").css("border", "3px solid green");
                            $("#text").css("border-left", " 2px dashed green");
                            $("#notif-textatas").css("color", "green");
                            $("#notif-textatas").html("Update berhasil!");
                            $("#notif-textbawah").html(
                                "Data informasi sekolah berhasil di update!"
                            );
                            $("#notif").toggle("display");
                            $("#notif").css("display", "flex");
                            let display = $("#notif").css("display");
                            if (display == "flex") {
                                setTimeout(() => {
                                    $("#notif").toggle("display");
                                }, 3000);
                            }
                        }
                    }
                })
            })
            $(".del-btn").click(function() {
                var data = $(this).data("data");
                if (confirm("Apakah Anda yakin ingin melanjutkan?")) {
                    $.ajax({
                        url: "hapus_data.php",
                        method: "POST",
                        data: {
                            data: data
                        },
                        success: function(result) {
                            if (result == "0") {
                                $("#notif-icon")
                                    .removeClass("bx-check-circle")
                                    .addClass("bx-x-circle");
                                $("#notif-icon").css("color", "crimson");
                                $("#notif").css("color", "crimson");
                                $("#notif").css("border", "3px solid crimson");
                                $("#text").css("border-left", " 2px dashed crimson");
                                $("#notif-textatas").css("color", "crimson");
                                $("#notif-textatas").html("Data " + data + " gagal dihapus!");
                                $("#notif-textbawah").html(
                                    "Proses penghapusan data " + data +
                                    " gagal,<br> mungkin terdapat keterkaitan dengan data <br> lain yang perlu diperiksa."
                                );
                                $("#notif").toggle("display");
                                $("#notif").css("display", "flex");
                                let display = $("#notif").css("display");
                                if (display == "flex") {
                                    setTimeout(() => {
                                        $("#notif").toggle("display");
                                    }, 3000);
                                }
                            } else {
                                $("#notif-icon")
                                    .removeClass("bx-x-circle")
                                    .addClass("bx-check-circle");
                                $("#notif-icon").css("color", "green");
                                $("#notif").css("color", "green");
                                $("#notif").css("border", "3px solid green");
                                $("#text").css("border-left", " 2px dashed green");
                                $("#notif-textatas").css("color", "green");
                                $("#notif-textatas").html("Data " + data +
                                    " berhasil dihapus!");
                                $("#notif-textbawah").html(
                                    "Seluruh data " + data + " berhasil di hapus!"
                                );
                                $("#notif").toggle("display");
                                $("#notif").css("display", "flex");
                                let display = $("#notif").css("display");
                                if (display == "flex") {
                                    setTimeout(() => {
                                        $("#notif").toggle("display");
                                    }, 3000);
                                }
                            }
                        }
                    })
                }
            })
            $("input[type='color']").on("input", function() {
                var varName = $(this).data("var");
                var color = $(this).val();
                if (varName == "border") {
                    $(":root").css("--border", "1px solid " + color);
                } else {
                    $(":root").css("--" + varName, color);
                }
            });

            if (localStorage.getItem("mode") == "dark") {
                $("body").addClass("dark-mode");
                $("#checkbox").prop("checked", true);
            }
            $("#checkbox").on("input", function() {
                if ($(this).is(":checked")) {
                    $("body").addClass("dark-mode");
                    localStorage.setItem("mode", "dark");
                } else {
                    $("body").removeClass("dark-mode");
                    localStorage.setItem("mode", "light");
                }
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
            $(document).on('click', '.walas-result', function() {
                var result = $(this).text();
                $('#wali_kelas').val(result);
                $('#walas-result').css('display', 'none');
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