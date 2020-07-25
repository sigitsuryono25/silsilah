<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Newsilsilah
 *
 * @author sigit
 */
class Newsilsilah extends CI_Controller {

    //put your code here
    function getListRaja($kategori) {
        $data['list_raja'] = $this->db->query("SELECT * FROM layanan INNER JOIN member_detail ON layanan.id_layanan=member_detail.id_kategori WHERE member_detail.id_kategori IN ('$kategori') AND parent_id IS NULL AND sebagai IN  ('raja')")->result();
        $this->load->view('new/daftar_raja', $data);
    }

    function getListKerjaan() {
        $listKerajaan = $this->db->query("SELECT * FROM layanan ORDER BY id_layanan ASC");
        foreach ($listKerajaan->result() as $kerajaan) {
            echo anchor(site_url('newsilsilah/getlistraja/' . $kerajaan->id_layanan), $kerajaan->nama_kategori) . "<br>";
        }
    }
    
    function getDetailByMemberId($memberId){
        $result = $this->db->query("SELECT * FROM member_detail WHERE member_id IN ('$memberId')")->row();
        echo json_encode($result);
    }

}
