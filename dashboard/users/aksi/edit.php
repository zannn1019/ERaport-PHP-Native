<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:../../../");
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
$detail = $_GET['data'];
$data_user = mysqli_fetch_assoc($user);
$nomor_induk = $_GET['nis'] ?? $_GET['nip'];
$data = $_GET['data'];
$jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan");
$no = 1;

if ($data == "siswa") {
    $siswa = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas WHERE nis = '$nomor_induk'");
    $data_siswa = mysqli_fetch_array($siswa);
    $id_kelas = $data_siswa['id_kelas'];
} elseif ($data == "guru") {
    $guru = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip = '$nomor_induk'");
    $data_guru = mysqli_fetch_array($guru);
    $data_mapel = mysqli_query($koneksi, "SELECT * FROM mapel");
} else {
    header("Location: ../../");
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
            <a class="icon-a dropdown-btn active <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="./../siswa/" class="d-list">Siswa</a></li>
                    <li><a href="./../guru/" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="../../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="../../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="../../ekskul/" class="icon-a <?= $role != 1 ?   'hidden'  : '' ?>"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a class="icon-a dropdown-btn <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../../nilai/akademik/" class="d-list">Akademik</a></li>
                    <li><a href="../../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <li><a href="../../nilai/ekskul/" class="d-list">Ekskul</a></li>
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
                    <span class="nav">Detail <?= $detail ?></span>
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
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box">
                        <?php switch ($data) {
                            case 'siswa': ?>
                                <h1>Edit <?= $data ?></h1>
                                <div class="form-edit">
                                    <form action="../aksi/aksi_edit.php" method="post" enctype="multipart/form-data">
                                        <div class="form-foto">
                                            <div class="preview-foto">
                                                <img alt="foto" src="<?= $data_siswa['foto'] ?>" alt="" id="imgPreview" width="210" height="210">
                                                <input type="file" name="foto" id="inputFoto" accept="Image/*">
                                                <span id="foto-hover"><i class='bx bx-image-add'></i>Ubah gambar</span>
                                            </div>
                                            <span>Foto siswa</span>
                                        </div>
                                        <div class="input-edit">
                                            <input type="hidden" name="id_siswa" value="<?= $data_siswa['id_siswa'] ?>">
                                            <span>Nama lengkap</span>
                                            <span>:</span>
                                            <input type="text" name="nama" value="<?= $data_siswa['nama'] ?>" required>
                                            <span>NIS</span>
                                            <span>:</span>
                                            <input type="text" name="nis" value="<?= $data_siswa['nis'] ?>" id="nis" required>
                                            <span></span>
                                            <span></span>
                                            <div class="cek-ni">
                                                <input type="checkbox" id="cek-ni" name="cek-ni" value="sama" checked>
                                                <span>NIS masih sama</span>
                                            </div>
                                            <span>Gmail</span>
                                            <span>:</span>
                                            <input type="email" name="email" value="<?= $data_siswa['email'] ?>" required>
                                            <span>Password</span>
                                            <span>:</span>
                                            <input type="text" name="password" value="<?= $data_siswa['password'] ?>" required>
                                            <span>Jurusan</span>
                                            <input type="hidden" name="" id="id_kelas" value="<?= $id_kelas ?>">
                                            <span>:</span>
                                            <select name="jurusan" id="jurusan" required>
                                                <option value="">Pilih Jurusan</option>
                                                <?php while ($row = mysqli_fetch_array($jurusan)) { ?>
                                                    <option value="<?= $row['id_jurusan'] ?>" <?= $data_siswa['id_jurusan'] == $row['id_jurusan'] ? 'selected' : '' ?>><?= $row['jurusan'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span>Kelas</span>
                                            <span>:</span>
                                            <select name="kelas" id="kelas" disabled required>
                                                <option value="">Pilih Kelas</option>
                                            </select>
                                            <span>Gender</span>
                                            <span>:</span>
                                            <div class="gender">
                                                <input type="radio" name="gender" id="" value="L" <?= $data_siswa['gender'] == 'L' ? 'checked' : '' ?> required>Laki-laki
                                                <input type="radio" name="gender" id="" value="P" <?= $data_siswa['gender'] == 'P' ? 'checked' : '' ?> required>Perempuan
                                            </div>
                                            <span>Agama</span>
                                            <span>:</span>
                                            <select name="agama" id="" required>
                                                <option value="">Pilih Agama</option>
                                                <option value="1" <?= $data_siswa['agama'] == "Islam" ? 'selected' : '' ?>>Islam</option>
                                                <option value="2" <?= $data_siswa['agama'] == "Kristen" ? 'selected' : '' ?>>Kristen</option>
                                                <option value="3" <?= $data_siswa['agama'] == "Katolik" ? 'selected' : '' ?>>Katolik</option>
                                                <option value="4" <?= $data_siswa['agama'] == "Hindu" ? 'selected' : '' ?>>Hindu</option>
                                                <option value="5" <?= $data_siswa['agama'] == "Buddha" ? 'selected' : '' ?>>Buddha</option>
                                                <option value="6" <?= $data_siswa['agama'] == "Konghucu" ? 'selected' : '' ?>>Konghucu</option>
                                            </select>
                                            <span>No. Telp</span>
                                            <span>:</span>
                                            <input type="text" name="no_telp" value="<?= $data_siswa['no_telp'] ?>" required>
                                            <span>Alamat</span>
                                            <span>:</span>
                                            <textarea name="alamat" id="" cols="30" rows="10" style="resize: none;" required><?= $data_siswa['alamat'] ?></textarea>
                                            <span>Nama Ortu</span>
                                            <span>:</span>
                                            <input type="text" name="nama_ortu" id="" value="<?= $data_siswa['nama_ortu'] ?>" required>
                                            <span>Status</span>
                                            <span>:</span>
                                            <select name="status" id="">
                                                <option value="Aktif" <?= $data_siswa['status'] == "Aktif" ? 'selected' : '' ?>>Aktif</option>
                                                <option value="Nonaktif" <?= $data_siswa['status'] == "Nonaktif" ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <input type="submit" value="Edit Siswa" class="submit-btn" name="edit-siswa">
                                    </form>
                                </div>
                            <?php break;
                            case 'guru': ?>
                                <h1>Edit <?= $data ?></h1>
                                <div class="form-edit">
                                    <form action="../aksi/aksi_edit.php" method="post" enctype="multipart/form-data">
                                        <div class="form-foto">
                                            <div class="preview-foto">
                                                <img alt="foto" src="<?= $data_guru['foto'] ?>" alt="" id="imgPreview" width="210" height="210">
                                                <input type="file" name="foto" id="inputFoto" accept="Image/*">
                                                <span id="foto-hover"><i class='bx bx-image-add'></i>Ubah gambar</span>
                                            </div>
                                            <span>Foto guru</span>
                                        </div>
                                        <div class="input-edit">
                                            <input type="hidden" name="id_guru" value="<?= $data_guru['id_guru'] ?>">
                                            <span>Nama lengkap</span>
                                            <span>:</span>
                                            <input type="text" name="nama" value="<?= $data_guru['nama'] ?>" required>
                                            <span>NIP</span>
                                            <span>:</span>
                                            <input type="text" name="nip" value="<?= $data_guru['nip'] ?>" id="nip" required>
                                            <span></span>
                                            <span></span>
                                            <div class="cek-ni">
                                                <input type="checkbox" id="cek-ni" name="cek-ni" checked>
                                                <span>NIP masih sama</span>
                                            </div>
                                            <span>Gmail</span>
                                            <span>:</span>
                                            <input type="email" name="email" value="<?= $data_guru['email'] ?>" required>
                                            <span>Password</span>
                                            <span>:</span>
                                            <input type="text" name="password" value="<?= $data_guru['password'] ?>" required>
                                            <span>Mapel</span>
                                            <span>:</span>
                                            <select name="mapel" id="">
                                                <option value="">-</option>
                                                <?php foreach ($data_mapel as $mapel) { ?>
                                                    <option value="<?= $mapel['id_mapel'] ?>" <?= $data_guru['id_mapel'] == $mapel['id_mapel'] ? 'selected' : ''  ?>><?= $mapel['mapel'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span>Gender</span>
                                            <span>:</span>
                                            <div class="gender">
                                                <input type="radio" name="gender" id="" value="L" <?= $data_guru['gender'] == 'L' ? 'checked' : '' ?> required>Laki-laki
                                                <input type="radio" name="gender" id="" value="P" <?= $data_guru['gender'] == 'P' ? 'checked' : '' ?> required>Perempuan
                                            </div>
                                            <span>Agama</span>
                                            <span>:</span>
                                            <select name="agama" id="" required>
                                                <option value="">Pilih Agama</option>
                                                <option value="1" <?= $data_guru['agama'] == "Islam" ? 'selected' : '' ?>>Islam</option>
                                                <option value="2" <?= $data_guru['agama'] == "Kristen" ? 'selected' : '' ?>>Kristen</option>
                                                <option value="3" <?= $data_guru['agama'] == "Katolik" ? 'selected' : '' ?>>Katolik</option>
                                                <option value="4" <?= $data_guru['agama'] == "Hindu" ? 'selected' : '' ?>>Hindu</option>
                                                <option value="5" <?= $data_guru['agama'] == "Buddha" ? 'selected' : '' ?>>Buddha</option>
                                                <option value="6" <?= $data_guru['agama'] == "Konghucu" ? 'selected' : '' ?>>Konghucu</option>
                                            </select>
                                            <span>No. Telp</span>
                                            <span>:</span>
                                            <input type="text" name="no_telp" value="<?= $data_guru['no_telp'] ?>" required>
                                            <span>Alamat</span>
                                            <span>:</span>
                                            <textarea name="alamat" id="" cols="30" rows="10" style="resize: none;" required><?= $data_guru['alamat'] ?></textarea>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <input type="submit" value="Edit Guru" class="submit-btn" name="edit-guru">
                                    </form>
                                </div>
                        <?php break;
                            default:
                                echo "<h1>Data ($data) Tidak valid</h1>";
                                break;
                        } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(window).on('load', function() {
            var id_jurusan = $("#jurusan").val();
            var id_kelas = $("#id_kelas").val();
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

        $(document).ready(function() {
            var checked = $("#cek-ni").is(':checked');
            if (checked == true) {
                $("#nis").attr("disabled", true);
                $("#nip").attr("disabled", true);
            } else {
                $("#nis").attr("disabled", false);
                $("#nip").attr("disabled", false);
            }

            $(".cek-ni").change(function() {
                var checked = $("#cek-ni").is(':checked');
                if (checked == true) {
                    $("#nis").attr("disabled", true);
                    $("#nip").attr("disabled", true);
                } else {
                    $("#nis").attr("disabled", false);
                    $("#nip").attr("disabled", false);
                }
            })
            $("#files").click(function() {
                $(".files").css("display", "flex");
            });
            $("#inputFoto").change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#imgPreview")
                            .attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
            $('#jurusan').on('change', function() {
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