<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('User_group_model');
    }

    /**
     * URL: BASE_URL/users/{user_id}
     * Method: GET
     */
    public function users_get()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken == false) {
                $output['code'] = REST_Controller::HTTP_UNAUTHORIZED;
                $output['message'] = "Unauthorized. Access token is missing or invalid.";
                $this->set_response($output, REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }

            $data['data'] = $this->User_model->getUser(1000);
            $this->set_response($data, REST_Controller::HTTP_OK);
        }
    }

}