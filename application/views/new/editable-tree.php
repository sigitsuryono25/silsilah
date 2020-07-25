<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Silsilah Bugis Makasar</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/silsilah.css">
    </head>
    <body>
        <div class="container-fluid">
            <?php if ($parent_node->num_rows() == 0) { ?>
                <button class="btn btn-sm btn-block btn-primary" onclick="showAddModal()">Tambah Raja</button>
            <?php } ?>
            <div class="row-wrapper center">
                <div class="main-wrapper child-wrapp">
                    <ul class="child-list main-section">
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal fade" style="z-index: 9999999" id="modal-silsilah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pop Up Manipulasi Silsilah</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formSilsilah">
                            <input type="hidden" value="" name="parent_id" />
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" id="nama" required="required" />
                            </div>
                            <div class="form-group">
                                <label>Sebagai</label>
                                <select class="form-control"  name="sebagai" id="sebagai" required>
                                    <option>--Silahkan Pilih--</option>
                                    <optgroup label="Lingkungan Kerjaan">                            
                                        <option value="Raja">Raja</option>
                                        <option value="Ratu">Ratu</option>
                                    </optgroup>
                                    <optgroup label="Lingkungan Rakyat Biasa">
                                        <option value="Istri">Istri</option>
                                        <option value="Suami">Suami</option>
                                        <option value="Anak">Anak</option>
                                    </optgroup>
                                </select>
                                <!--<input type="text" class="form-control"  />-->
                            </div>  
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jk" id="laki" value="Laki-Laki" required="">
                                    <label class="form-check-label" for="laki">
                                        Laki-Laki
                                    </label>
                                </div>   
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jk" id="perempuan"  value="Perempuan" required="">
                                    <label class="form-check-label" for="perempuan">
                                        Perempuan
                                    </label>
                                </div>   
                            </div>
                            <div class="form-group">
                                <label>Estimated (Tahun)</label>
                                <input type="text" class="form-control" name="berkuasa-pada" id="berkuasa-pada-tahun" />
                            </div>
                            <div class="form-group">
                                <label>Gelar</label>
                                <input type="text" class="form-control" name="gelar" id="gelar"  />
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" value="Submit" class="btn btn-block btn-danger"/>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script>
                    var clone = $("#modal-silsilah").clone();
                    $(document).ready(function () {
                        mainSection();
                    });
                    
                    function mainSection() {
                        var url = "<?php echo site_url('silsilah/test?id-node=' . $this->input->get('id-node')) ?>";
                        $.get(url, null, function (data) {
                            $(".main-section").html(data);
                            $(".member-id").each(function () {
                                childSection($(this).html());
                            });
                            $('.popover-edit').popover({
                                container: 'body',
                                html: true
                            });
                        });
                    }
                    function childSection(parentId) {
                        console.log(parentId);
                        var url = "<?php echo site_url('silsilah/anoterMember/') ?>" + parentId + "/false";
                        $.get(url, null, function (data) {
                            $(".child-section").html(data);
                            $('.popover-edit').popover({
                                container: 'body',
                                html: true,
                                title: 'Tindakan',
//                        trigger: 'focus',
//                        content: `<a href='javascript:void(0)' class='editButton' onclick='showEditModal(`+parentId+`)'>Edit</a><a href='#' class='deleteButton'>Hapus</a>`
                            });
                        });
                    }
                    function showEditModal(memberId) {
                        $("#formSilsilah")[0].reset();
                        var url = "<?php echo site_url('newsilsilah/getDetailByMemberId/') ?>" + memberId;
                        $.get(url, null, function (data) {
                            var memberId = data.member_id;
                            var parentId = data.parent_id;
                            var nama = data.nama;
                            var memberImg = data.member_img;
                            var sebagai = data.sebagai;
                            var berkuasaPada = data.berkuasa_pada;
                            var gelar = data.gelar;
                            var jk = data.jk;
                            var generasi = data.generasi;
                            var idNode = data.id_node;
                            console.log(jk);
                            $('[name="nama"]').val(nama);
                            $('[name="sebagai"]').val(sebagai);
                            if (jk == 'Laki-Laki') {
                                $("#laki").prop("checked", "true");
                            } else if (jk == "Perempuan") {
                                $("#perempuan").prop("checked", "true");
                            }
                            $('[name="berkuasa-pada"]').val(berkuasaPada);
                            $('[name="gelar"]').val(gelar);
                            $("#modal-silsilah").modal("show");
                        }, "JSON");
                        $("#formSilsilah").on("submit", function (e) {
                            e.preventDefault();
                            var url = "<?php echo site_url('silsilah/updateNode?id-node=' . $this->input->get('id-node') . "&member-id=") ?>" + memberId;
                            var data = $(this).serialize();
                            $.post(url, data, function (data) {
                                var code = data.code;
                                if (code == 200) {
                                    alert("berhasil diubah");
                                    location.reload();
                                } else {
                                    alert("Somethin' wrong");
                                }
                            }, "JSON");
                        });
                    }

                    function showAddModal(memberId = null, jk = null) {
                        $('[name="parent_id"]').val(memberId);
                        $("#modal-silsilah").modal("show");
                        $("#formSilsilah").on("submit", function (e) {
                            e.preventDefault();
                            var url = "<?php echo site_url('silsilah/proc_add_member?id-node=' . $this->input->get('id-node')) ?>";
                            var data = $(this).serialize();
                            $.post(url, data, function (data) {
                                var code = data.code;
                                if (code == 200) {
                                    alert("berhasil ditambahkan");
                                    location.reload();
                                } else {
                                    alert("Somethin' wrong");
                                }
                            }, "JSON");
                        });
                    }

                    function deleteData(node) {
                        var url = "<?php echo site_url('silsilah/deleteNode/') ?>" + node + "?id-node=<?php echo $this->input->get('id-node') ?>";
                        var c = confirm("Hapus data ini?");
                        if (c) {
                            $.get(url, null, function (data) {
                                console.log(JSON.stringify(data));
                                var code = data.code;
                                if (code == '200') {
                                    alert('Hapus berhasil');
                                    location.assign("<?php echo site_url('silsilah/newAddMember?id-node=' . $this->input->get('id-node')) ?>");
                                } else {
                                    alert('Oooppss.. something wrong');
                                }
                            }, 'JSON');
                        }
                    }
        </script>
    </body>
</html>