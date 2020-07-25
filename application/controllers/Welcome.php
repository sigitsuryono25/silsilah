<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $this->load->view('welcome_message');
    }

    public function data_keluarga() {
//	{	$idsummary_keluarga = $this->input->get("unique-id");
//		$suami = $this->db->query("SELECT * FROM data_kpl WHERE id_summary IN ('$idsummary_keluarga')")->row();
//		$istri = $this->db->query("SELECT * FROM tbl_ibu WHERE id_kpl IN ('$suami->id_kpl')")->result();
//		$data['pasangan'] = [];
//		foreach($istri as $keys=>$ist){
//			$tmp = [];
//			$idIstri = $ist->id;
//			$values = "$suami->id_kpl-$idIstri";
//			$ayah = $suami->ayah;
//			$ibu = $ist->nama;
//			$pasanganTampil = "$ayah-$ibu";
//
//			$tmp["values$keys"] = $values;
//			$tmp["view$keys"] = $pasanganTampil;
//
//			array_push($data['pasangan'], $tmp);
//		}
        $this->load->view('data-keluarga');
    }

    public function tambah_keluarga() {
        //ke summary
        $idsummary_keluarga = $this->input->post('idSummary');
        $checkIfExist = $this->db->query("SELECT * FROM summary_keluarga WHERE id_summary IN ('$idsummary_keluarga')")->row();
        if (empty($checkIfExist)) {
            $datasummary_keluarga = ['id_summary' => $idsummary_keluarga];
            $resSum = $this->db->insert('summary_keluarga', $datasummary_keluarga);
        }

        //ke data-keluarga
        $idKpl = $this->input->post('idKpl');
        $ayah = $this->input->post('ayah');
        $checkIfExist = $this->db->query("SELECT * FROM data_kpl WHERE id_kpl IN ('$idKpl')")->row();
        print_r($checkIfExist);
        if (empty($checkIfExist)) {
            $dataKpl = ['id_kpl' => $idKpl, 'ayah' => $ayah, 'id_summary' => $idsummary_keluarga];
            $resKpl = $this->db->insert('data_kpl', $dataKpl);
        }
        //ke data istri
        $ibu = $this->input->post('ibu');
        $idIbu = time();
        if (!empty($ibu)) {
            foreach ($ibu as $ib) {
                $dataIstri = ['id_kpl' => $idKpl, 'nama' => $ib];

                $resIstri = $this->db->insert('tbl_ibu', $dataIstri);
            }
        }

        if ($resSum == true && $resKpl == true && $resIstri == true) {
            echo json_encode(['code' => 200, 'message' => $checkIfExist]);
        } else {
            echo json_encode(['code' => 500, 'message' => $checkIfExist]);
        }
    }

    function getPasangan($idSummary) {
        $suami = $this->db->query("SELECT * FROM data_kpl WHERE id_summary IN ('$idSummary')")->row();
        $istri = $this->db->query("SELECT * FROM tbl_ibu WHERE id_kpl IN ('$suami->id_kpl')")->result();
        foreach ($istri as $ist) {
            $idIstri = $ist->id;
            $values = "$suami->id_kpl-$idIstri";
            $ayah = $suami->ayah;
            $ibu = $ist->nama;
            $pasanganTampil = "$ayah-$ibu";

            echo "<option value='$values'>" . $pasanganTampil . "</option>";
        }
    }

    function tambahAnak() {
        $idKeluarga = $this->input->post('id-keluarga');


        $idIbu = explode("-", $idKeluarga)[1];
        $namaAnak = $this->input->post("anak");

        if (!empty($namaAnak)) {
            foreach ($namaAnak as $key => $nama) {
                $dataAnak = ['id_anak' => time() . $idIbu . "$key", 'nama_anak' => $nama, 'id_ibu' => $idIbu];

                $resAnak = $this->db->insert('data_anak', $dataAnak);
            }
        }
        if ($resAnak) {
            echo json_encode(['code' => 200]);
        } else {
            echo json_encode(['code' => 500]);
        }
    }

}
