<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Silsilah
 *
 * @author sigit
 */
class Silsilah extends CI_Controller {

//put your code here

    function addMember() {
        $idNode = $this->input->get('id-node');
        $data['parent'] = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idNode') AND parent_id IS NULL ORDER BY parent_id, member_id ASC")->result();
        $this->load->view('add-member', $data);
    }

    function newAddMember() {
        $idNode = $this->input->get('id-node');
        $data['parent_node'] = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idNode')");
        $this->load->view('new/editable-tree', $data);
    }

    function proc_add_member() {
        $parentId = empty($this->input->post('parent_id')) ? null : $this->input->post('parent_id');
        $nama = $this->input->post('nama');
        $sebagai = $this->input->post('sebagai');
        $member_img = $this->input->post('member_img');
        $berkuasaPhoto = $this->input->post('berkuasa-pada');
        $gelar = $this->input->post('gelar');
        $jk = $this->input->post("jk");
        $idNode = $this->input->get('id-node');

        $dataInsertMember = [
            'id_kategori' => $idNode,
            'parent_id' => $parentId,
            'nama' => $nama,
            'member_img' => $member_img,
            "sebagai" => $sebagai,
            'jk' => $jk,
            'berkuasa_pada' => $berkuasaPhoto,
            'gelar' => $gelar];
        $res = $this->member->insertDb('member_detail ', $dataInsertMember);

        if ($res > 0) {
            echo json_encode(['code' => 200]);
        } else {
            echo json_encode(['code' => 500]);
        }
    }

    function getFamilyTree() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        $res = $this->db->query("SELECT * FROM member_detail ORDER BY member_id")->result();
        $data = [];
        foreach ($res as $r) {
            $tmp = [];
            $tmp['memberId'] = $r->member_id;
            $tmp['parentId'] = $r->parent_id;
            $tmp['nama'] = $r->nama;
            $tmp['sebagai'] = $r->sebagai;
            $tmp['berkuasa_pada'] = $r->berkuasa_pada;
            $tmp['jk'] = $r->jk;
            $tmp['gelar'] = $r->gelar;

            array_push($data, $tmp);
        }

        echo json_encode($data);
    }

    function showFamilyTree() {
        $this->load->view("tree-view");
    }

    function showFamilyTreeMobile() {
        $this->load->view("tree-view-mobile");
    }

    function showFamilyTreeDesktop() {
        $this->load->view("tree-view-desktop");
    }

    function resetTable() {
        $this->db->empty_table('member_detail');
        redirect('silsilah/addmember', 'refresh');
    }

    function deleteNode($node, $parent = false) {
        if ($parent == false) {
            $where = ['member_id' => $node];
            $check = $this->db->query("SELECT * FROM member_detail WHERE parent_id IN ('$node')");
            $delete = $this->member->deleteDb('member_detail', $where);
            if ($delete > 0) {
                if ($check->num_rows() > 0) {
                    $parentId = ['parent_id' => $node];
                    $deleteNode = $this->member->deleteDb('member_detail', $parentId);
                    if ($deleteNode > 0) {
                        echo json_encode(['code' => 200]);
                    }
                }
                echo json_encode(['code' => 200]);
            }
        }
    }

    function updateNode() {
        $memberId = $this->input->get("member-id");

        $nama = $this->input->post('nama');
        $sebagai = $this->input->post('sebagai');
        $berkuasaPada = $this->input->post('berkuasa-pada');
        $gelar = $this->input->post('gelar');
        $jk = $this->input->post("jk");

        $where = ['member_id' => $memberId];

        $dataupdate = ['nama' => $nama, 'sebagai' => $sebagai, 'berkuasa_pada' => $berkuasaPada, 'gelar' => $gelar, 'jk' => $jk];

        $res = $this->member->updateDb('member_detail', $dataupdate, $where);
        if ($res > 0) {
            echo json_encode(['code' => 200]);
        } else {
            echo json_encode(['code' => 500]);
        }
    }

    function formUpdate() {
        $parentId = $this->input->get("member-id");
        $data['parent'] = $this->db->query("SELECT * FROM member_detail WHERE parent_id IS NULL ORDER BY parent_id, member_id ASC")->result();
        $data['member'] = $this->db->query("SELECT * FROM member_detail WHERE member_id IN ('$parentId')")->row();
        $parentIdBefore = $data['member']->parent_id;
        $data['before'] = $this->db->query("SELECT * FROM member_detail WHERE member_id IN ('$parentIdBefore')")->row();
        $this->load->view('edit-member', $data);
    }

    function treeTest() {
        $this->load->view('tree-test');
    }

    function test() {
        $idNode = $this->input->get('id-node');
        $sequence = 0;
        $raja = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE layanan.id_layanan IN ('$idNode') AND sebagai IN ('raja', 'ratu')")->result();
        foreach ($raja as $r) {
            if ($r->sebagai == "Raja" && $r->parent_id == NULL) {
                echo '<li class = "child male king ">';
                echo '<div tabindex = "-1"  class = "wrapp with-pict btn popover-edit" data-toggle="popover" data-trigger="focus" data-content = "'
                . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class ='popover-header'>Tindakan</h3><div class = 'popover-body'>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
                . "</div></div>"
                . '">';
                echo "<h3>$r->nama</h3>";
                echo "<span class='member-id' style='display: none'>$r->member_id</span>";
                echo '<p class = "label-datu">' . $r->gelar . '</p>';
                echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
                echo '<p class = "label-title">' . $r->sebagai . '</p>';
                echo '</div>';
            } else if ($r->sebagai == "Ratu") {
                $ratu = $this->db->query("SELECT COUNT(*) as jumlah FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE layanan.id_layanan IN ('$idNode') AND sebagai IN ('ratu')")->row();
                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id");
                if ($ratu->jumlah > 1) {
                    $checkmember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE member_detail.parent_id IN ('" . $an->parent_id . "') GROUP BY member_detail.member_id");
                    if ($checkmember->num_rows() > 1) {
                        $counts = $countMember->row()->member;
                        if ($last == true) {
                            if ($counts > 1) {
                                echo "<li class='child female queen last'>";
                            } else {
                                echo "<li class='child female queen last tunggal'>";
                            }
                        } else {
                            if ($counts > 1) {
                                echo "<li class='child female queen first poligami'>";
                            } else {
                                echo "<li class='child female queen tunggal poligami'>";
                            }
                        }
                    } else {
                        $counts = $countMember->row()->member;
                        if ($last == true) {
                            if ($counts > 1) {
                                echo "<li class='child female queen last'>";
                            } else {
                                echo "<li class='child female queen last tunggal'>";
                            }
                        } else {
                            if ($counts > 1) {
                                echo "<li class='child female queen first'>";
                            } else {
                                echo "<li class='child female queen tunggal'>";
                            }
                        }
                    }
                    echo '<div tabindex = "-1"  class = "wrapp btn popover-edit" data-toggle="popover" data-trigger="focus" data-content = "'
                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class ='popover-header'>Tindakan</h3><div class = 'popover-body'>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
                    . "</div></div>"
                    . '">';
                    echo "<h3>$r->nama</h3>";
                    echo "<span class='member-id' style='display: none'>$r->member_id</span>";
                    echo '<p class = "label-datu">' . $r->gelar . '</p>';
                    echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
                    echo '<p class = "label-title">' . $r->sebagai . '</p>';
                    echo '</div>';
                } else {
                    echo '<li class = "child female wife queen">';
                    echo '<div tabindex = "-1"  class = "wrapp btn popover-edit" data-toggle="popover" data-trigger="focus" data-content = "'
                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class ='popover-header'>Tindakan</h3><div class = 'popover-body'>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
                    . "</div></div>"
                    . '">';
                    echo "<h3>$r->nama</h3>";
                    echo "<span class='member-id' style='display: none'>$r->member_id</span>";
                    echo '<p class = "label-datu">' . $r->gelar . '</p>';
                    echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
                    echo '<p class = "label-title">' . $r->sebagai . '</p>';
                    echo '</div>';
                }
            }
            if ($this->anoterMember($r->member_id, false, $idNode)) {
                echo "</li>";
            }
            $sequence++;
        }
    }

    function anoterMember($parentId = null, $last = false, $idNode = null) {
        error_reporting(0);
        $anoterMember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE  parent_id IN ('$parentId') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id");
        $count = $anoterMember->num_rows();
        foreach ($anoterMember->result() as $key => $an) {
            if ($an->jk == "Laki-Laki" && $an->generasi == 1) {
                if ($key == $count - 1) {
                    $this->anoterMember($an->member_id, true, $idNode);
                    echo "<li class='child male last'>";
                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                    . "</div></div>"
                    . '">';
                    echo "<h3>$an->nama </h3>";
                    if ($an->is_raja == 1) {
                        echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                    }
                    echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                    echo "</div>";
                    echo "</li>";
                } else if ($key == 0) {
                    echo "<li class='child male first'>";
                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                    . "</div></div>"
                    . '">';
                    echo "<h3>$an->nama </h3>";
                    if ($an->is_raja == 1) {
                        echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                    }
                    echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                    echo "</div>";
                    $this->anoterMember($an->member_id, false, $idNode);
                    echo "</li>";
                } else {
                    echo "<li class='child male '>";
                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                    . "</div></div>"
                    . '">';
                    echo "<h3>$an->nama </h3>";
                    if ($an->is_raja == 1) {
                        echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                    }
                    echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                    echo "</div>";
                    $this->anoterMember($an->member_id, false, $idNode);
                    echo "</li>";
                }

                // AS HUSBAND
            } else if ($an->jk == "Laki-Laki" && $an->sebagai == 'Suami') {
                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id");

                $checkmember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE member_detail.parent_id IN ('" . $an->parent_id . "') GROUP BY member_detail.member_id");
                if ($checkmember->num_rows() > 1) {
                    $counts = $countMember->row()->member;
                    if ($last == true) {
                        if ($counts > 1) {
                            echo "<li class='child male husband last'>";
                        } else {
                            echo "<li class='child male husband last tunggal'>";
                        }
                    } else {
                        if ($counts > 1) {
                            echo "<li class='child male husband first poligami'>";
                        } else {
                            echo "<li class='child male husband tunggal poligami'>";
                        }
                    }
                } else {
                    $counts = $countMember->row()->member;
                    if ($last == true) {
                        if ($counts > 1) {
                            echo "<li class='child male husband last'>";
                        } else {
                            echo "<li class='child male husband last tunggal'>";
                        }
                    } else {
                        if ($counts > 1) {
                            echo "<li class='child male husband first'>";
                        } else {
                            echo "<li class='child male husband tunggal'>";
                        }
                    }
                }

                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                . '">';
                echo "<h3>$an->nama </h3>";
                echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                echo "</div>";
                $this->anoterMember($an->member_id, false, $idNode);
                echo "</li>";
            } else if ($an->jk == "Laki-Laki") {
                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id");
                $counts = $countMember->row()->member;
                echo '<div class = "main-wrapper child-wrapp">';
                echo "<ul class='child-list'>";
                if ($count == 1) {
                    echo "<li class='child male'>";
                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                    . '">';

                    echo "<h3>$an->nama </h3>";
                    if ($an->is_raja == 1) {
                        echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                    }
                    echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                    echo "</div>";
                    $this->anoterMember($an->member_id, false, $idNode);
                    echo "</li>";
                    echo "</ul>";
                    echo "</div>";
                } else {
                    if ($key == 0) {
                        echo "<li class='child male first'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>$an->nama </h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                        echo "</div>";
                        $this->anoterMember($an->member_id, false, $idNode);
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    } else if ($key == $count - 1) {
                        $this->anoterMember($an->member_id, true, $idNode);
                        echo "<li class='child male last'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>$an->nama</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                        echo "</div>";
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        echo "<li class='child male '>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>$an->nama </h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                        echo "</div>";
                        $this->anoterMember($an->member_id, false, $idNode);
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    }
                }
            } else if ($an->jk == "Perempuan" && $an->sebagai == "Istri") {
                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id");

                $checkmember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE member_detail.parent_id IN ('" . $an->parent_id . "') GROUP BY member_detail.member_id");
                if ($checkmember->num_rows() > 1) {
                    $counts = $countMember->row()->member;
                    if ($last == true) {
                        if ($counts > 1) {
                            echo "<li class='child female wife last x'>";
                        } else if ($counts !== 0) {
                            echo "<li class='child female wife last tunggal x'>";
                        }
                    } else {
                        if ($counts > 1) {
                            echo "<li class='child female wife first poligami y'>";
                        } else {
//                                    echo "SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE member_detail.parent_id IN ('" . $an->parent_id . "') GROUP BY member_detail.member_id";
                            echo "<li class='child female wife tunggal poligami y dua'>";
                        }
                    }
                } else {
                    $counts = $countMember->row()->member;
                    if ($last == true) {
                        if ($counts > 1) {
                            echo "<li class='child female wife last'>";
                        } else if ($counts !== 0) {
                            echo "<li class='child female wife last tunggal'>";
                        }
                    } else {
                        if ($counts > 1) {
                            echo "<li class='child female wife first'>";
                        } else if ($counts !== 0) {
                            echo "<li class='child female wife tunggal poligami'>";
                        }
                    }
                }

                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                . '">';
                echo "<h3>$an->nama </h3>";
                echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                echo "</div>";
                $this->anoterMember($an->member_id, false, $idNode);
                echo "</li>";
            } else if ($an->jk == "Perempuan" && $an->sebagai == "Anak") {
                echo '<div class = "main-wrapper child-wrapp">';
                echo "<ul class='child-list'>";
                if ($count == 0) {
                    echo "<li class='child female'>";
                } else {
                    if ($key == 0) {
                        echo "<li class='child female first'>";
                    } else if ($key == $count - 1) {
                        echo "<li class='child female last'>";
                    } else {
                        echo "<li class='child female '>";
                    }
                }
                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                . '">';
                echo "<h3>$an->nama </h3>";
                echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>$an->gelar</p>";
                echo "</div>";
                echo "</li>";
                $this->anoterMember($an->member_id, false, $idNode);
                echo "</ul>";
                echo "</div>";
            }
        }
    }

//
//    function test() {
//        $idNode = $this->input->get('id-node');
//        $sequence = 0;
//        $raja = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE layanan.id_layanan IN ('$idNode') AND sebagai IN ('raja', 'ratu')")->result();
//        foreach ($raja as $r) {
//            if ($r->sebagai == "Raja") {
//                echo '<li class = "child male king ">';
//                echo '<div tabindex = "-1"  class = "wrapp with-pict btn popover-edit" data-toggle="popover" data-trigger="focus" data-content = "'
//                . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class ='popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
//                . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
//                . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
//                . "</div></div>"
//                . '">';
//                echo '<div class="pict-wrapp">
//                <img src = "' . base_url('assets/') . 'img/male.jpeg" alt = "Raja">
//                </div>';
//                echo "<h3>$r->nama</h3>";
//                echo "<span class='member-id' style='display: none'>$r->member_id</span>";
//                echo '<p class = "label-datu">' . $r->gelar . '</p>';
//                echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
//                echo '<p class = "label-title">' . $r->sebagai . '</p>';
//                echo '</div>';
//            } else {
//                $ratu = $this->db->query("SELECT COUNT(*) as jumlah FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE layanan.id_layanan IN ('$idNode') AND sebagai IN ('ratu')")->row();
//                if ($ratu->jumlah > 1) {
//                    if ($sequence == $ratu->jumlah) {
//                        echo '<li class = "child female wife queen">';
//                    } else {
//                        echo '<li class = "child female wife queen poligami">';
//                    }
//                    echo '<div tabindex = "-1"  class = "wrapp btn popover-edit" data-toggle="popover" data-trigger="focus" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class ='popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$r->nama</h3>";
//                    echo "<span class='member-id' style='display: none'>$r->member_id</span>";
//                    echo '<p class = "label-datu">' . $r->gelar . '</p>';
//                    echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
//                    echo '<p class = "label-title">' . $r->sebagai . '</p>';
//                    echo '</div>';
//                } else {
//                    echo '<li class = "child female wife queen">';
//                    echo '<div tabindex = "-1"  class = "wrapp btn popover-edit" data-toggle="popover" data-trigger="focus" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class ='popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$r->nama</h3>";
//                    echo "<span class='member-id' style='display: none'>$r->member_id</span>";
//                    echo '<p class = "label-datu">' . $r->gelar . '</p>';
//                    echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
//                    echo '<p class = "label-title">' . $r->sebagai . '</p>';
//                    echo '</div>';
//                }
//            }
//            if ($this->anoterMember($r->member_id, false, $idNode)) {
//                echo "</li>";
//            }
//            $sequence++;
//        }
//    }
//
//    function anoterMember($parentId = null, $last = false, $idNode = null) {
//        error_reporting(0);
//        $anoterMember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE  parent_id IN ('$parentId') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id");
//        $count = $anoterMember->num_rows();
//        foreach ($anoterMember->result() as $key => $an) {
//            if ($an->jk == "Laki-Laki" && $an->generasi == 1) {
//                if ($key == $count - 1) {
//                    $this->anoterMember($an->member_id, true, $idNode);
//                    echo "<li class='child male last'>";
//                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$an->nama </h3>";
//                    echo "</div>";
//                    echo "</li>";
//                } else if ($key == 0) {
//                    echo "<li class='child male first'>";
//                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$an->nama </h3>";
//                    echo "</div>";
//                    $this->anoterMember($an->member_id, false, $idNode);
//                    echo "</li>";
//                } else {
//                    echo "<li class='child male '>";
//                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$an->nama </h3>";
//                    echo "</div>";
//                    $this->anoterMember($an->member_id, false, $idNode);
//                    echo "</li>";
//                }
//            } else if ($an->jk == "Laki-Laki" && $an->sebagai == 'Suami') {
//                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id");
//                $counts = $countMember->row()->member;
//                if ($count == 1) {
//                    echo "<li class='child male husband'>";
//                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$an->nama </h3>";
//                    echo "</div>";
//                    $this->anoterMember($an->member_id, false, $idNode);
//                    echo "</li>";
//                } else {
//                    if ($key == 0) {
//                        echo "<li class='child male first husband'>";
//                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                        . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                        . "</div></div>"
//                        . '">';
//                        echo "<h3>$an->nama </h3>";
//                        echo "</div>";
//                        $this->anoterMember($an->member_id, false, $idNode);
//                        echo "</li>";
//                    } else if ($key == $count - 1) {
//                        $this->anoterMember($an->member_id, true, $idNode);
//                        echo "<li class='child male last husband'>";
//                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                        . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                        . "</div></div>"
//                        . '">';
//                        echo "<h3>$an->nama</h3>";
//                        echo "</div>";
//                        echo "</li>";
//                    } else {
//                        echo "<li class='child male husband'>";
//                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                        . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                        . "</div></div>"
//                        . '">';
//                        echo "<h3>$an->nama </h3>";
//                        echo "</div>";
//                        $this->anoterMember($an->member_id, false, $idNode);
//                        echo "</li>";
//                    }
//                }
//            } else if ($an->jk == "Laki-Laki") {
//                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id");
//                $counts = $countMember->row()->member;
//                echo '<div class = "main-wrapper child-wrapp">';
//                echo "<ul class='child-list'>";
//                if ($count == 1) {
//                    echo "<li class='child male'>";
//                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                    . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                    . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                    . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                    . "</div></div>"
//                    . '">';
//                    echo "<h3>$an->nama </h3>";
//                    echo "</div>";
//                    $this->anoterMember($an->member_id, false, $idNode);
//                    echo "</li>";
//                    echo "</ul>";
//                    echo "</div>";
//                } else {
//                    if ($key == 0) {
//                        echo "<li class='child male first'>";
//                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                        . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                        . "</div></div>"
//                        . '">';
//                        echo "<h3>$an->nama </h3>";
//                        echo "</div>";
//                        $this->anoterMember($an->member_id, false, $idNode);
//                        echo "</li>";
//                        echo "</ul>";
//                        echo "</div>";
//                    } else if ($key == $count - 1) {
//                        $this->anoterMember($an->member_id, true, $idNode);
//                        echo "<li class='child male last'>";
//                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                        . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                        . "</div></div>"
//                        . '">';
//                        echo "<h3>$an->nama</h3>";
//                        echo "</div>";
//                        echo "</li>";
//                        echo "</ul>";
//                        echo "</div>";
//                    } else {
//                        echo "<li class='child male '>";
//                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                        . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                        . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                        . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                        . "</div></div>"
//                        . '">';
//                        echo "<h3>$an->nama </h3>";
//                        echo "</div>";
//                        $this->anoterMember($an->member_id, false, $idNode);
//                        echo "</li>";
//                        echo "</ul>";
//                        echo "</div>";
//                    }
//                }
//            } else if ($an->jk == "Perempuan" && $an->sebagai == "Istri") {
//                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id");
//
//                $checkmember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE member_detail.parent_id IN ('" . $an->parent_id . "') GROUP BY member_detail.member_id");
//                if ($checkmember->num_rows() > 1) {
//                    foreach ($checkmember->result() as $key => $poli) {
//                        if (1 + $key !== $checkmember->num_rows()) {
//                            $counts = $countMember->row()->member;
//                            if ($last == true) {
//                                if ($counts > 1) {
//                                    echo "<li class='child female wife last'>";
//                                } else {
//                                    echo "<li class='child female wife last tunggal'>";
//                                }
//                            } else {
//                                if ($counts > 1) {
//                                    echo "<li class='child female wife first poligami'>";
//                                } else {
//                                    echo "<li class='child female wife tunggal poligami'>";
//                                }
//                            }
//                        }
//                    }
//                } else {
//                    $counts = $countMember->row()->member;
//                    if ($last == true) {
//                        if ($counts > 1) {
//                            echo "<li class='child female wife last'>";
//                        } else {
//                            echo "<li class='child female wife last tunggal'>";
//                        }
//                    } else {
//                        if ($counts > 1) {
//                            echo "<li class='child female wife first'>";
//                        } else {
//                            echo "<li class='child female wife tunggal'>";
//                        }
//                    }
//                }
//
//                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                . "</div></div>"
//                . '">';
//                echo "<h3>$an->nama </h3>";
//                echo "</div>";
//                $this->anoterMember($an->member_id, false, $idNode);
//                echo "</li>";
//            } else if ($an->jk == "Perempuan" && $an->sebagai == "Anak") {
//                echo '<div class = "main-wrapper child-wrapp">';
//                echo "<ul class='child-list'>";
//                if ($count == 0) {
//                    echo "<li class='child female'>";
//                } else {
//                    if ($key == 0) {
//                        echo "<li class='child female first'>";
//                    } else if ($key == $count - 1) {
//                        echo "<li class='child female last'>";
//                    } else {
//                        echo "<li class='child female '>";
//                    }
//                }
//                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-member = "' . $an->member_id . '" data-content = "'
//                . "<div class = 'popover' role = 'tooltip'><div class = 'arrow'></div><h3 class = 'popover-header'>Tindakan</h3><div class = 'popover-body'>"
//                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
//                . "<a href = 'javascript:void(0)' class = 'editButton' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
//                . "<a href = 'javascript:void(0)' class = 'deleteButton' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
//                . "</div></div>"
//                . '">';
//                echo "<h3>$an->nama </h3>";
//                echo "</div>";
//                echo "</li>";
//                $this->anoterMember($an->member_id, false, $idNode);
//                echo "</ul>";
//                echo "</div>";
//            }
//        }
//    }
}
