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

    function getDetailMember($memberId) {
        $data['detail'] = $this->db->query("SELECT * FROM member_detail WHERE member_id IN ('$memberId')")->row();
        $this->load->view('new/detail', $data);
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
        $raja = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE layanan.id_layanan IN ('$idNode') AND sebagai IN ('raja') ORDER BY member_detail.member_id ASC")->result();
        foreach ($raja as $r) {
            echo '<li class = "child male king ">';
            echo '<div tabindex = "-1"  class = "wrapp with-pict btn popover-edit" data-toggle="popover" data-trigger="focus" onclick="showDetail(`' . $r->member_id . '`)"  data-content = "'
            . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
            . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
            . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
            . '">';
            echo '<div  data-toggle="tooltip" title="Hooray!">';
            echo "<h3>" . substr_replace($r->nama, '...', 15) . "</h3>";
            echo "<span class='member-id' style='display: none'>$r->member_id</span>";
            echo '<p class = "label-datu">' . substr_replace($r->gelar, '...', 15) . '</p>';
            echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
            echo '<p class = "label-title">' . $r->sebagai . '</p>';
            echo '</div>';
            echo '</div>';
            $this->getRatu($r->member_id, $idNode);
            echo "</li>";
        }
    }

    function getRatu($parentId = null, $idNode = null) {
        error_reporting(0);
        $ratu = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE layanan.id_layanan IN ('$idNode') AND parent_id IN ('$parentId') AND sebagai IN ('ratu', 'istri') ORDER BY member_detail.member_id ASC");
        $count = $ratu->num_rows();
        if ($ratu->num_rows() > 1) {
            foreach ($ratu->result() as $key => $r) {
                if ($key == $count - 1) {
                    echo "<li class='child female wife " . $this->checkMember($r->member_id, $idNode) . "'>";
                } else if ($key == 0) {
                    echo "<li class='child female wife first queen poligami " . $this->checkMember($r->member_id, $idNode) . "'>";
                } else {
                    echo "<li class='child female wife  poligami " . $this->checkMember($r->member_id, $idNode) . "'>$r->";
                }

                echo '<div tabindex = "-1"  class = "wrapp btn popover-edit" data-toggle="popover" data-trigger="focus" onclick="showDetail(`' . $r->member_id . '`)"   data-content = "'
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
                . '">';
                echo "<h3>" . substr_replace($r->nama, '...', 10) . "</h3>";
                echo '<p class = "label-datu">' . substr_replace($r->gelar, '...', 15) . '</p>';
                echo "<span class='member-id' style='display: none'>$r->member_id</span>";
                echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
                if ($key == 0) {
                    echo '<p class = "label-title">' . $r->sebagai . '</p>';
                }
                echo '</div>';
                $this->anoterMember($r->member_id, false, $idNode);
            }
        } else if ($ratu->num_rows() != 0) {
            $r = $ratu->row();
            echo "<li class = 'child female wife queen " . $this->checkMember($r->member_id, $idNode) . "'>";
            echo '<div tabindex = "-1"  class = "wrapp btn popover-edit" data-toggle="popover" data-trigger="focus" onclick="showDetail(`' . $r->member_id . '`)"   data-content = "'
            . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $r->member_id . "`, `" . $r->jk . "`)'>Add</a>"
            . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $r->member_id . ")'>Edit</a>"
            . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $r->member_id . ")'>Delete</a>"
            . '">';
            echo "<h3>" . substr_replace($r->nama, '...', 10) . "</h3>";
            echo '<p class = "label-datu">' . substr_replace($r->gelar, '...', 15) . '</p>';
            echo "<span class='member-id' style='display: none'>$r->member_id</span>";
            echo '<p class = "year">' . $r->berkuasa_pada . '</p>';
            echo '<p class = "label-title">' . $r->sebagai . '</p>';
            echo '</div>';
            $this->anoterMember($r->member_id, false, $idNode);
        }
    }

    function anoterMember($parentId = null, $last = false, $idNode = null, $sort = "ASC", $islast = "first") {
        error_reporting(0);
        $anoterMember = $this->db->query("SELECT * FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE  parent_id IN ('$parentId') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id  $sort");
        $count = $anoterMember->num_rows();
        foreach ($anoterMember->result() as $key => $an) {
            //as a husband
            if ($an->jk == "Laki-Laki" && $an->sebagai == 'Suami') {
                $whosiscall = $this->whoiscall($an->member_id, $idNode);
                $nextMember = $this->db->query("SELECT COUNT(*) as countmember FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id $sort")->row();
                $isPoligami = $this->db->query("SELECT *, COUNT(*) as poligami FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->parent_id . "') AND sebagai NOT IN ('ratu', 'raja', 'anak', 'istri') ORDER BY member_detail.member_id $sort")->row();
                $checkLastMember = $this->db->query("SELECT * FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('$an->parent_id') AND id_kategori IN ('$idNode') ORDER BY member_detail.member_id DESC LIMIT 1")->row();
                if ($whosiscall == "Perempuan" && $islast == "last") {
                    $tunggal = ($nextMember->countmember > 1) ? "" : "tunggal";
                    $poligami = ($isPoligami->poligami > 1 && $an->member_id != $checkLastMember->member_id) ? "poligami" : "";
                    $last = "last";
                    echo "<li class='child male husband $last $tunggal $poligami'>";
                } else {
                    $tunggal = ($nextMember->countmember > 1) ? "" : "tunggal";
                    $poligami = ($isPoligami->poligami > 1) ? "poligami" : "";
                    $last = "first";
                    echo "<li class='child male husband $last $tunggal $poligami'>";
                }
                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-trigger="focus"   onclick="showDetail(`' . $an->member_id . '`)"  data-member = "' . $an->member_id . '" data-content = "'
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                . '">';
                echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                echo "</div>";
                $this->anoterMember($an->member_id, false, $idNode);
                echo "</li>";

                //as a son
            } else if ($an->jk == "Laki-Laki" && $an->sebagai == "Anak") {
                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id  $sort");
                $nextMember = $this->db->query("SELECT *, COUNT(*) as countmember FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id $sort")->row();
                $counts = $countMember->row()->member;
                echo '<div class = "main-wrapper child-wrapp">';
                echo "<ul class='child-list'>";
                if ($count == 1) {
                    if($islast == "last"){
                        $this->anoterMember($an->member_id, false, $idNode, "ASC", "last");
                    }
                    echo "<li class='child male'>";
                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"   data-member = "' . $an->member_id . '" data-content = "'
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                    . '">';

                    echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                    if ($an->is_raja == 1) {
                        echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                    }
                    echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                    echo "</div>";
                    if($islast !== "last"){
                        $this->anoterMember($an->member_id, false, $idNode, "ASC", "first");
                    }
                    echo "</li>";
                    echo "</ul>";
                    echo "</div>";
                } else {
                    if ($key == 0) {
                        echo "<li class='child male first'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover" data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"    data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                        echo "</div>";
                        $this->anoterMember($an->member_id, false, $idNode, "ASC", "first");
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    } else if ($key == $count - 1) {
                        $this->anoterMember($an->member_id, true, $idNode, "DESC", "last");
                        echo "<li class='child male last'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover" data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"    data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                        echo "</div>";
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        echo "<li class='child male '>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover" data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"    data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                        echo "</div>";
                        $this->anoterMember($an->member_id, false, $idNode, "DESC", "first");
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    }
                }
            }
            //as a wife
            else if ($an->jk == "Perempuan" && $an->sebagai == "Istri") {

                $whosiscall = $this->whoiscall($an->member_id, $idNode);
                $nextMember = $this->db->query("SELECT COUNT(*) as countmember FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') ORDER BY member_detail.member_id $sort")->row();
                $isPoligami = $this->db->query("SELECT *, COUNT(*) as poligami FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->parent_id . "') AND sebagai NOT IN ('ratu', 'raja', 'anak', 'suami') ORDER BY member_detail.member_id $sort")->row();
                $checkLastMember = $this->db->query("SELECT * FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('$an->parent_id') AND id_kategori IN ('$idNode') ORDER BY member_detail.member_id DESC LIMIT 1")->row();

                if ($whosiscall == "Laki-Laki" && $islast == "last") {
                    $tunggal = ($nextMember->countmember > 1) ? "" : "tunggal";
                    $poligami = ($isPoligami->poligami > 1 && $an->member_id != $checkLastMember->member_id) ? "poligami" : "";
                    $last = "last";
                    echo "<li class='child female wife $last $tunggal $poligami'>";
                } else {
                    $tunggal = ($nextMember->countmember > 1) ? "" : "tunggal";
                    $poligami = ($isPoligami->poligami > 1 && $an->member_id != $checkLastMember->member_id) ? "poligami" : "";
                    $last = "first";
                    echo "<li class='child female wife $last $tunggal $poligami'>";
                }
                echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-trigger="focus" onclick="showDetail(`' . $an->member_id . '`)"    data-member = "' . $an->member_id . '" data-content = "'
                . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                . '">';
                echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                echo "</div>";
                $this->anoterMember($an->member_id, false, $idNode, "ASC", $last);
                echo "</li>";

                //as a daughter
            } else if ($an->jk == "Perempuan" && $an->sebagai == "Anak") {
                $countMember = $this->db->query("SELECT COUNT(*) as member FROM `layanan` INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE parent_id IN ('" . $an->member_id . "') AND sebagai NOT IN ('ratu', 'raja') GROUP BY member_detail.member_id ORDER BY member_detail.member_id $sort");
                $counts = $countMember->row()->member;
                echo '<div class = "main-wrapper child-wrapp">';
                echo "<ul class='child-list'>";
                if ($count == 1) {
                    echo "<li class='child female children'>";
                    echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover" data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"   data-member = "' . $an->member_id . '" data-content = "'
                    . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                    . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                    . '">';

                    echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                    if ($an->is_raja == 1) {
                        echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                    }
                    echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                    echo "</div>";
                    $this->anoterMember($an->member_id, false, $idNode);
                    echo "</li>";
                    echo "</ul>";
                    echo "</div>";
                } else {
                    if ($key == 0) {
                        echo "<li class='child female first children'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover" data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"    data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                        echo "</div>";
                        $this->anoterMember($an->member_id, false, $idNode, "ASC", "first");
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    } else if ($key == $count - 1) {
                        $this->anoterMember($an->member_id, true, $idNode, "DESC", "last");
                        echo "<li class='child female last children'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"   data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                        echo "</div>";
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    } else {
                        $this->anoterMember($an->member_id, false, $idNode, "ASC", "last");
                        echo "<li class='child female children'>";
                        echo '<div tabindex = "-1" class = "wrapp  btn  popover-edit" data-toggle = "popover"  data-trigger="focus"  onclick="showDetail(`' . $an->member_id . '`)"   data-member = "' . $an->member_id . '" data-content = "'
                        . "<a href = 'javascript:void(0)' class = 'btn btn-primary btn-sm btn-block' onclick = 'showAddModal(`" . $an->member_id . "`, `" . $an->jk . "`)'>Add</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-warning btn-sm btn-block' onclick = 'showEditModal(" . $an->member_id . ")'>Edit</a>"
                        . "<a href = 'javascript:void(0)' class = 'btn btn-danger btn-sm btn-block' onclick = 'deleteData(" . $an->member_id . ")'>Hapus</a>"
                        . '">';

                        echo "<h3>" . substr_replace($an->nama, '...', 10) . "</h3>";
                        if ($an->is_raja == 1) {
                            echo "<p class='label-title' style='background-color: #0D6FAD; color: #FFF'><b>Raja</b></p>";
                        }
                        echo empty($an->gelar) ? "<p class = 'label-datu'>-</p>" : "<p class = 'label-datu'>" . substr_replace($an->gelar, '...', 15) . "</p>";
                        echo "</div>";
                        echo "</li>";
                        echo "</ul>";
                        echo "</div>";
                    }
                }
            }
        }
    }

    private function checkMember($member, $idNode) {
        $member = $this->db->query("SELECT * FROM member_detail WHERE parent_id IN ('$member') AND id_kategori IN ('$idNode')")->num_rows();
//        echo "SELECT * FROM member_detail WHERE parent_id IN ('$member') AND id_kategori IN ('$idNode')";
        if ($member == 1) {
            return "tunggal";
        }
    }

    private function whoiscall($member, $idNode) {
        $member = $this->db->query("SELECT * FROM member_detail WHERE member_id IN (SELECT parent_id FROM `member_detail` WHERE member_id IN ('$member') AND id_kategori IN ('$idNode')) AND id_kategori IN ('$idNode')")->row();
        return $member->jk;
    }

}
