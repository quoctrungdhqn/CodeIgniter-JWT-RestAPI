<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_group_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getAllUserGroup()
    {
        $query = $this->db->get('cp_users_groups');

        foreach ($query->result_array() as $row) {
            $data[$row['id']] = $row['groupName'];
        }
        return $data;
    }
}

?>