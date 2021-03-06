<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Member_m
 *
 * @author sigit
 */
class Member_m extends CI_Model {

//put your code here

    function insertDb($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->affected_rows();
    }

    function deleteDb($table, $where) {
        $this->db->where($where);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    function updateDb($table, $data, $where) {
        $this->db->where($where);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }

    function wordLimiter($input, $limit) {
        $string = strip_tags($input);
        if (strlen($string) > $limit) {

            // truncate string
            $stringCut = substr($string, 0, $limit);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= '... <a href="/this/story">Read More</a>';
        }
        return $string;
    }

}
