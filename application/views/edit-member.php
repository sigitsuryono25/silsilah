<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Description" content="Enter your description here"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/ddsmoothmenu.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/ddsmoothmenu-v.css" />
        <title>Add Family Member</title>
    </head>
    <body>
        <div class="container-fluid p-5">
            <div class="row">
                <div class="col-md-12"><a class="btn btn-block btn-primary" href="<?php echo site_url('silsilah/showFamilyTree') ?>">View Member Tree</a></div>
            </div>

            <form method="post" action="<?php echo site_url('silsilah/updateNode?member-id=' . $this->input->get('member-id')) ?>">
                <?php if (!empty($parent)) { ?>
                    <div class="form-group">
                        <label>Pilih Parent</label>
                        <!--call sub parent-->
                        <?php
                        $parentId = $_GET['member-id'];

                        function submenu_v($member_id) {
                            $memberId = $_GET['member-id'];
                            $con = new mysqli("localhost", "root", "", "test");
                            $getParentId = mysqli_query($con, "SELECT * FROM member_detail WHERE member_id IN  ('$memberId')");
                            $dataParent = mysqli_fetch_array($getParentId);

                            $sql_2 = "SELECT * FROM member_detail WHERE parent_id=$member_id ORDER BY parent_id, member_id ASC";
                            $items_2 = mysqli_query($con, $sql_2) or die(mysql_error());
                            $key = 0;
                            if (mysqli_num_rows($items_2) > 0) {
                                ?><ul><?php
                                    while ($row_2 = mysqli_fetch_array($items_2)) {
                                        ?>			
                                        <li>
                                            <a class="">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="parent_id" id="parent_id" value="<?php echo $row_2['member_id']; ?>">
                                                    <label class="form-check-label" for="inlineRadio1">
                                                        <?php echo $row_2['nama'] ?>    
                                                    </label>
                                                </div>
                                                <br/>
                                                <div class="border d-none">
                                                    <span class="text-danger font-weight-bold btn-sm" style="cursor: pointer" onclick="deleteData('<?php echo $row_2['member_id']; ?>')">Hapus</span>
                                                    <span class="text-warning font-weight-bold btn-sm">Edit</span>
                                                </div>
                                            </a>

                                            <?php
//                                            if ($row_2['member_id'] <= $dataParent['parent_id']) {
//                                                echo "</li>";
//                                                break;
//                                            } else 
                                            if (submenu_v($row_2['member_id']) == "") {
                                                ?></li><?php
                                        }
                                        $key++;
                                    }
                                    ?></ul><?php
                                }
                            }
                            ?>

                        <div id="smoothmenu2" class="ddsmoothmenu-v">
                            <ul>
                                <?php foreach ($parent as $p) { ?>
                                    <li>
                                        <a class="bg-success">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="parent_id" id="parent_id" value="<?php echo $p->member_id ?>">
                                                <label class="form-check-label" for="inlineRadio1">
                                                    <?php echo $p->nama ?> 
                                                </label>
                                            </div>     
                                            <br/>
                                            <div class="border d-none">
                                                <span class="text-danger font-weight-bold btn-sm" style="cursor: pointer" onclick="deleteData('<?php echo $p->member_id ?>')">Hapus</span>
                                                <span class="text-warning font-weight-bold btn-sm ">Edit</span>
                                            </div>
                                        </a>

                                        <?php
//                                        if ($p->member_id <= $before->parent_id) {
//                                            echo "</li>";
//                                            break;
//                                        } else 
                                        if (submenu_v($p->member_id) == "") {
                                            ?>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <label><b>Keluarga diatas dari <?php echo $member->nama ?>: <?php echo $before->nama ?></b></label><br/>
                            <small>Note: Nggak usah pilih kalo nggak mau ganti parent nya</small>
                            <small class="red">Penggantian Parent hanya dapat memilih generasi yang ada diatas dari <?php echo $member->nama ?></small>
                        </div>

                    </div>
                <?php } else { ?>
                    <div class="form-group text-center">
                        <label class="mt-3">Belum Ada Root Node</label>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" id="nama" required="required" value="<?php echo $member->nama ?>" />
                    <input type="hidden" name="selected-parent-id" value="<?php echo $member->parent_id ?>" />
                    <input type="hidden" name="member-id" value="<?php echo $member->member_id ?>" />
                    <input type="hidden" name="parent-id-now" value="<?php echo $member->parent_id ?>" />
                </div>

                <div class="form-group">
                    <label>Sebagai</label>
                    <select class="form-control"  name="sebagai" id="sebagai" required>
                        <option>--Silahkan Pilih--</option>
                        <optgroup label="Lingkungan Kerjaan">                            
                            <option value="Raja" <?php echo ($member->sebagai == "Raja") ? "selected" : "" ?>>Raja</option>
                            <option value="Ratu" <?php echo ($member->sebagai == "Ratu") ? "selected" : "" ?>>Ratu</option>
                            <option value="Pangeran" <?php echo ($member->sebagai == "Pangeran") ? "selected" : "" ?>>Pangeran</option>
                            <option value="Putri" <?php echo ($member->sebagai == "Putri") ? "selected" : "" ?>>Putri</option>
                        </optgroup>
                        <optgroup label="Lingkungan Rakyat Biasa">
                            <option value="Istri" <?php echo ($member->sebagai == "Istri") ? "selected" : "" ?>>Istri</option>
                            <option value="Suami" <?php echo ($member->sebagai == "Suami") ? "selected" : "" ?>>Suami</option>
                            <option value="Anak" <?php echo ($member->sebagai == "Anak") ? "selected" : "" ?>>Anak</option>
                        </optgroup>
                    </select>
                    <!--<input type="text" class="form-control"  />-->
                </div>  
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" id="jk" value="Laki-Laki" required="" <?php echo ($member->jk == "Laki-Laki") ? "checked" : "" ?>>
                        <label class="form-check-label" for="jk">
                            Laki-Laki
                        </label>
                    </div>   
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jk" id="jk" value="Perempuan" required=""<?php echo ($member->jk == "Perempuan") ? "checked" : "" ?>>
                        <label class="form-check-label" for="jk">
                            Perempuan
                        </label>
                    </div>   
                </div>
                <div class="form-group">
                    <label>Estimated (Tahun)</label>
                    <input type="text" class="form-control" name="berkuasa-pada" id="berkuasa-pada-tahun" value="<?php echo $member->berkuasa_pada ?>" />
                </div>
                <div class="form-group">
                    <label>Gelar</label>
                    <input type="text" class="form-control" name="gelar" id="gelar"  value="<?php echo $member->gelar ?>"/>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Submit" class="btn btn-block btn-danger"/>
                </div>
            </form>

            <div class="border p-4">
                <h3>Gimana Cara Pake nya?</h3>
                <ol>
                    <li class="text-justify">Kalo database nya kosong, dibagian atas bakal bilang belum ada Root Node. Silahkan isi dulu parent utamanya, dalam hal ini jumlah yang paling kecil. Bila
                        jumlah raja lebih kecil dibandingkan dengan Ratu maka Raja harus di input terlebih dahulu. Ini juga berlaku untuk ratu, jika jumlah ratu lebih kecil maka Ratu harus 
                        di inputkan dahulu.</li>
                    <li>Jika sudah memili root/titik yang paling atas maka akan muncul pilih parent. Pilih parent untuk data selanjutnya. Data yang di inputkan akan muncul dibawah dari parent yang dipilih</li>
                    <li>Kalo mau ngosongin silsilah, klik ini <a class="btn btn-danger" onclick="return confirm('Hapus Semua Data?')" href="<?php echo site_url('silsilah/resetTable') ?>">HAPUS SEMUA SILSILAH</a></li>
                </ol>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/js/ddsmoothmenu.js"></script>
        <script type="text/javascript">
                                ddsmoothmenu.init({
                                mainmenuid: "smoothmenu2", //Menu DIV id
                                        orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
                                        classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
                                        customtheme: ["#1c5a80", "#18374a"],
                                        contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
                                });
                                function deleteData(node) {
                                let url = "<?php echo site_url('silsilah/deleteNode/') ?>" + node + "?id-node=<?php $this->input->get('id-node') ?>";
                                        let c = confirm("Hapus data ini?");
                                        if (c) {
                                $.get(url, null, function (data) {
                                console.log(JSON.stringify(data));
                                        let code = data.code;
                                        if (code == '200') {
                                alert('Hapus berhasil');
                                        location.assign("<?php echo site_url('silsilah/addMember') ?>");
                                } else {
                                alert('Oooppss.. something wrong');
                                }
                                }, 'JSON');
                                }
                                }

                        $('[name="parent_id"]').on("click", function (e) {
                        let selectedId = $(this).val();
                                let selectedParentId = $('[name="parent-id-now"]').val();
                                let memberId = $('[name="member-id"]').val();
                                if (selectedId > selectedParentId || selectedId == memberId) {
                        alert("Anda tidak bisa mengubah parent ke generasi dibawahnya atau anggota yang diedit sekarang");
                                this.checked = false;
                                e.preventDefault();
                                e.stopPropagation();
                        } else {
                        $('[name="selected-parent-id"]').val(selectedId);
                        }
                        });
        </script>
    </body>
</html>

