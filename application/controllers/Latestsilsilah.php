<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LatestSilsilah
 *
 * @author sigit
 */
class Latestsilsilah extends CI_Controller {

    //put your code here

    function getinitialnode() {
        $idNode = $this->input->get('id-node');
        $parent = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idNode') AND parent_id IS NULL ORDER BY parent_id, member_id ASC")->row();
        echo "<ul>";
        echo "<li>";
        echo $parent->nama;
        if (empty($this->getRatu($idNode, $parent->member_id))) {
            echo "</li>";
        }
        echo "</ul>";
    }

    function getRatu($idnode, $memberid) {
        $ratu = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idnode') AND parent_id IN ('$memberid')ORDER BY parent_id, member_id ASC")->result();
        echo "<li>";
        foreach ($ratu as $r) {
            echo $r->nama;
            if (empty($this->getanother($idnode, $r->member_id))) {
                echo "</li>";
            }
        }
    }

    function getanother($idnode, $memberid) {
        $parent = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idnode') AND parent_id IN ('$memberid')ORDER BY parent_id, member_id ASC")->result();

        foreach ($parent as $member) {
            if ($member->jk == "Laki-Laki") {
                $this->getLakiLaki($idnode, $member, true);
            } else if ($member->jk == "Perempuan") {
                $this->getPerempuan($idnode, $member, true);
            }
        }
    }

    function getLakiLaki($idnode, $member, $isRoot = true, $sebagai = "Anak") {
        $memberId = $member->member_id;
        $parent = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idnode') AND parent_id IN ('$memberId')ORDER BY parent_id, member_id ASC")->result();
        if ($isRoot) {
            echo '<div class="main-wrapper child-wrapp">';
            echo "<ul>";
            if ($sebagai == "Suami") {
                echo "<li class='husband'>";
            } else {
                echo "<li>";
            }
            echo $member->nama;
            echo "</li>";
            foreach ($parent as $p) {
                if ($p->jk == "Laki-Laki") {
                    $this->getLakiLaki($idnode, $p, false);
                } else if ($p->jk == "Perempuan") {
                    $this->getPerempuan($idnode, $p, false);
                }
            }
            echo "</ul>";
            echo '</div>';
        } else {
            if ($sebagai == "Suami") {
                echo "<li class='husband'>";
            } else {
                echo "<li>";
            }
            echo $member->nama;
            foreach ($parent as $p) {
                if ($p->jk == "Laki-Laki") {
                    $this->getLakiLaki($idnode, $p, true);
                } else if ($p->jk == "Perempuan") {
                    $this->getPerempuan($idnode, $p, true);
                }
            }
            echo "</li>";
        }
    }

    function getPerempuan($idnode, $member, $isRoot = true) {
        $memberId = $member->member_id;
        $parent = $this->db->query("SELECT * FROM member_detail WHERE id_kategori IN ('$idnode') AND parent_id IN ('$memberId')ORDER BY parent_id, member_id ASC")->result();
        if ($isRoot == true) {
            echo '<div class="main-wrapper child-wrapp">';
            echo "<ul>";
            echo "<li>";
            echo $member->nama;
            echo "</li>";
            foreach ($parent as $p) {
                if ($p->jk == "Laki-Laki") {
                    $this->getLakiLaki($idnode, $p, false);
                } else if ($p->jk == "Perempuan") {
                    $this->getPerempuan($idnode, $p, false);
                }
            }
            echo "</ul>";
            echo '</div>';
        } else {
            echo "<li>";
            echo $member->nama;
            foreach ($parent as $p) {
                if ($p->jk == "Laki-Laki") {
                    $this->getLakiLaki($idnode, $p, true);
                } else if ($p->jk == "Perempuan") {
                    $this->getPerempuan($idnode, $p, true);
                }
            }
            echo "</li>";
        }
    }

}
