<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (!in_array($_SESSION['role'], [2])) {
    header("location:../../../");
    session_destroy();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_guru = $_SESSION['id_guru'] ?? null;

$id_role = $role;
if ($role == 1) {
    $user = mysqli_query($koneksi, "SELECT * FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE username = '$username'");
} elseif ($role == 2) {
    $user = mysqli_query($koneksi, "SELECT * FROM guru INNER JOIN role ON guru.id_role = role.id_role INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE guru.nama = '$username'");
    $cek_wakel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas = '$id_guru'"));
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}

$data_user = mysqli_fetch_assoc($user);
$nilai = $_GET['nilai'];
$id_nilai = $_GET['id'];

if ($nilai == "akademik") {
    $data_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa WHERE id_nilai = '$id_nilai'"));
} elseif ($nilai == "absensi") {
    $data_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM absensi INNER JOIN siswa ON absensi.id_siswa = siswa.id_siswa WHERE id_absen = '$id_nilai'"));
} else {
    $data_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM data_ekskul INNER JOIN siswa ON data_ekskul.id_siswa = siswa.id_siswa WHERE id_data = '$id_nilai'"));
}

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
            <a class="icon-a dropdown-btn active <?= $role = ($role == 1 || $role == 2) ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons icons">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../akademik/" class="d-list">Akademik</a></li>
                    <?php if ($cek_wakel != null) { ?>
                        <li><a href="../../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <?php } ?>
                </ul>
            </div>
            <a href="../../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Log Out</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Edit Nilai <?= $nilai ?></span>
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
                        <img alt="foto" src="../../../asset/img/admin/<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
            </div>
            <div class="col-div-8 ">
                <div class="box-8 mb-5">
                    <div class="content-box">
                        <div class="tb-tittle">
                            <span></span>
                            <h1>Data <?= $nilai  ?></h1>
                            <span></span>
                        </div>
                        <?php
                        switch ($nilai) {
                            case 'akademik': ?>
                                <div class="form-edit">
                                    <form action="aksi_edit.php" method="post">
                                        <div class="input-edit">
                                            <input type="hidden" name="id_nilai" value="<?= $data_nilai['id_nilai'] ?>">
                                            <span>Nama Siswa</span>
                                            <span>:</span>
                                            <div class="search" style="position: relative;  ">
                                                <input style=" width: -moz-available;width: -webkit-fill-available;width: fill-available;" type="text" id="search-siswa" name="nama_siswa" placeholder="Masukkan Nama/NIS siswa" value="<?= $data_nilai['nama'] ?> (<?= $data_nilai['nis'] ?>)" required>
                                                <div class="search-field" id="search-field">
                                                </div>
                                            </div>
                                            <span>Semester</span>
                                            <span>:</span>
                                            <select name="semester" id="" required>
                                                <option value="">Pilih semester</option>
                                                <?php
                                                $query = mysqli_query($koneksi, "SELECT * FROM semester");
                                                foreach ($query as $semester) { ?>
                                                    <option value="<?= $semester['id_semester'] ?>" <?= $semester['id_semester'] == $data_nilai['id_semester'] ? 'selected' : '' ?>><?= $semester['semester'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span>Nilai Harian</span>
                                            <span>:</span>
                                            <input type="number" min="0" max="100" id="harian" name="harian" value="<?= $data_nilai['nilai_harian'] ?>" required>
                                            <span>Nilai UTS</span>
                                            <span>:</span>
                                            <input type="number" min="0" max="100" id="uts" name="uts" value="<?= $data_nilai['nilai_uts'] ?>" required>
                                            <span>Nilai UAS</span>
                                            <span>:</span>
                                            <input type="number" min="0" max="100" id="uas" name="uas" value="<?= $data_nilai['nilai_uas'] ?>" required>
                                            <span>Nilai Pengetahuan</span>
                                            <span>:</span>
                                            <input type="number" min="0" max="100" id="nilai-p" name="nilai-p" value="<?= $data_nilai['nilai_pengetahuan'] ?>" required>
                                            <span>Predikat</span>
                                            <span>:</span>
                                            <input type="text" id="grade-p" name="grade-p" value="<?= $data_nilai['grade_pengetahuan'] ?>" required>
                                            <span>Desk Pengetahuan</span>
                                            <span>:</span>
                                            <input type="text" name="desk-p" value="<?= $data_nilai['desk_pengetahuan'] ?>" required>
                                            <span>Nilai Keterampilan</span>
                                            <span>:</span>
                                            <input type="number" min="0" max="100" id="nilai-k" name="nilai-k" value="<?= $data_nilai['nilai_keterampilan'] ?>" required>
                                            <span>Predikat</span>
                                            <span>:</span>
                                            <input type="text" id="grade-k" name="grade-k" value="<?= $data_nilai['grade_ket'] ?>" required>
                                            <span>Desk Keterampilan</span>
                                            <span>:</span>
                                            <input type="text" name="desk-k" value="<?= $data_nilai['desk_ket'] ?>" required>
                                            <?php if ($data_user['jenis'] == "kejuruan") { ?>
                                                <span>NIlai UKK</span>
                                                <span>:</span>
                                                <input type="text" name="ukk" value="<?= $data_nilai['nilai_ukk'] ?>" required>
                                                <span>Desk UKK</span>
                                                <span>:</span>
                                                <input type="text" name="desk-ukk" value="<?= $data_nilai['desk_ukk'] ?>" required>
                                            <?php }  ?>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <input type="submit" value="Edit Nilai" name="edit-akademik" class="submit-btn">
                                    </form>
                                </div>
                            <?php break;
                            case 'absensi': ?>
                                <div class="form-edit">
                                    <form action="../aksi/aksi_edit.php" method="post" enctype="multipart/form-data" class="form-user">
                                        <div class="input-edit">
                                            <input type="hidden" name="id_absen" value="<?= $id_nilai ?>">
                                            <span>Nama Siswa</span>
                                            <span>:</span>
                                            <div class="search" style="position: relative;  ">
                                                <input style=" width: -moz-available;          
    width: -webkit-fill-available;  
    width: fill-available;" type="text" id="search-siswa" name="nama_siswa" placeholder="Masukkan Nama/NIS siswa" value="<?= $data_nilai['nama'] ?> (<?= $data_nilai['nis'] ?>)" required>
                                                <div class="search-field" id="search-field">
                                                </div>
                                            </div>
                                            <span>Semester</span>
                                            <span>:</span>
                                            <select name="semester" id="" required>
                                                <option value="">Pilih semester</option>
                                                <?php
                                                $query = mysqli_query($koneksi, "SELECT * FROM semester");
                                                foreach ($query as $semester) { ?>
                                                    <option value="<?= $semester['id_semester'] ?>" <?= $semester['id_semester'] == $data_nilai['id_semester'] ? 'selected' : '' ?>><?= $semester['semester'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span>Izin</span>
                                            <span>:</span>
                                            <input type="number" name="izin" id="" value="<?= $data_nilai['izin'] ?>" required>
                                            <span>Sakit</span>
                                            <span>:</span>
                                            <input type="number" name="sakit" id="" value="<?= $data_nilai['sakit'] ?>" required>
                                            <span>Tanpa Keterangan</span>
                                            <span>:</span>
                                            <input type="number" name="alfa" id="" value="<?= $data_nilai['alfa'] ?>" required>
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
                                        </div>
                                        <input type="submit" value="Edit Kehadiran" class="submit-btn" name="edit-absensi">
                                    </form>
                                </div>
                            <?php break;
                            case "ekskul": ?>
                                <div class="form-edit">
                                    <form action="./aksi_edit.php" method="post" enctype="multipart/form-data" class="form-user">
                                        <div class="input-edit">
                                            <input type="hidden" name="id_ekskul" value="<?= $id_nilai ?>">
                                            <span>Nama Siswa</span>
                                            <span>:</span>
                                            <div class="search" style="position: relative;  ">
                                                <input style=" width: -moz-available;          
    width: -webkit-fill-available;  
    width: fill-available;" type="text" id="search-siswa" name="nama_siswa" placeholder="Masukkan Nama/NIS siswa" value="<?= $data_nilai['nama'] ?> (<?= $data_nilai['nis'] ?>)" required>
                                                <div class="search-field" id="search-field">
                                                </div>
                                            </div>
                                            <span>Nilai</span>
                                            <span>:</span>
                                            <input type="number" name="nilai_ekskul" id="" value="<?= $data_nilai['nilai'] ?>">
                                            <span>Keterangan</span>
                                            <span>:</span>
                                            <input type="text" name="keterangan" id="" value="<?= $data_nilai['keterangan'] ?>">
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
                                        </div>
                                        <input type="submit" value="Edit Nilai " class="submit-btn" name="edit-nilai-ekskul">
                                    </form>
                                </div>
                        <?php
                                break;
                            default:
                                header("Location: ../../");
                                break;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(document).on("click", function(event) {
            if (!$(event.target).is("#files, .files")) {
                $(".files").css("display", "none");
            }
        });

        function search(keyword) {
            $.ajax({
                url: 'get_siswa.php',
                type: 'POST',
                data: {
                    search: keyword
                },
                dataType: 'html',
                success: function(result) {
                    $("#search-field").html(result)
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            })
            if (keyword == '') {
                $("#search-field").css("display", "none");
            }
        }
        $(document).ready(function() {
            $("#harian,#uts,#uas").change(function() {
                var harian = parseInt($("#harian").val())
                var uts = parseInt($("#uts").val())
                var uas = parseInt($("#uas").val())
                var pengetahuan = parseInt((harian + uts + uas) / 3)
                var grade = hitungPredikat(pengetahuan)
                $("#nilai-p").val(pengetahuan)
                $("#grade-p").val(grade)
            })
            $("#nilai-k").change(function() {
                var keterampilan = parseInt($(this).val())
                var grade = hitungPredikat(keterampilan)
                $("#grade-k").val(grade)
            })
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
            $("#search-siswa").on('keyup', function() {
                $('#search-field').css("display", 'inline-block')
                search($(this).val())
            })
            $(document).on('click', '.search-pilihan', function() {
                var result = $(this).text();
                $('#search-siswa').val(result);
                $('#search-field').css("display", 'none')
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

            function hitungPredikat(nilai) {
                let kategorinilai = [
                    ['A', 90, 100],
                    ['B', 80, 89],
                    ['C', 70, 79],
                    ['D', 60, 69],
                    ['E', 0, 59]
                ];
                let predikat;
                kategorinilai.forEach(function(kn) {
                    if (nilai >= kn[1] && nilai <= kn[2]) {
                        predikat = kn[0];
                        return false;
                    }
                });
                return predikat;
            }
        });
    </script>
</body>

</html>