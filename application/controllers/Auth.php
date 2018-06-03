<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/*
 * Changes:
 * 1. This project contains .htaccess file for windows machine.
 *    Please update as per your requirements.
 *    Samples (Win/Linux): http://stackoverflow.com/questions/28525870/removing-index-php-from-url-in-codeigniter-on-mandriva
 *
 * 2. Change 'encryption_key' in application\config\config.php
 *    Link for encryption_key: http://jeffreybarke.net/tools/codeigniter-encryption-key-generator/
 * 
 * 3. Change 'jwt_key' in application\config\jwt.php
 * 3. Change 'token_timeout' in application\config\jwt.php
 *
 */

class Auth extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * URL: BASE_URL/auth/login
     * Method: POST
     */
    public function login_post()
    {
        $CI =& get_instance();

        if (isset($_GET['username']) && isset($_GET['password'])
            && isset($_GET['client_id']) && isset($_GET['client_secret'])) {
            $client_id = $_GET['client_id'];
            $client_secret = $_GET['client_secret'];
            $username = $_GET['username'];
            $password = md5($_GET['password']);

            $local_client_id = $CI->config->item('client_id');
            $local_client_secret = $CI->config->item('client_secret');

            if ($client_id != $local_client_id || $client_secret != $local_client_secret) {
                $output['code'] = REST_Controller::HTTP_NOT_FOUND;
                $output['message'] = "Client ID or Client Secret is invalid.";
                $this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
                return;
            }

            $this->load->model('User_model');
            $user = $this->User_model->checkLogin($username, $password);
            if ($user) {
                $newData = array(
                    'loggedAdmin' => true,
                    'userLogged' => $user
                );
                $this->session->set_userdata($newData);

                $tokenData['timestamp'] = now();
                $output['access_token'] = AUTHORIZATION::generateToken($tokenData);
                $output['expires_in'] = $CI->config->item('token_timeout') * 60 . " seconds";
                $this->set_response($output, REST_Controller::HTTP_OK);
            } else {
                $output['code'] = REST_Controller::HTTP_BAD_REQUEST;
                $output['message'] = "Login fail. Wrong username or password.";
                $this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
            }

        }
    }

    /**
     * URL: BASE_URL/auth/refresh_token
     * Method: GET
     * Used: Access Token is expired
     */
    public function refresh_token_post()
    {
        $CI =& get_instance();
        if (isset($_GET['client_id']) && isset($_GET['client_secret'])) {
            $client_id = $_GET['client_id'];
            $client_secret = $_GET['client_secret'];

            $local_client_id = $CI->config->item('client_id');
            $local_client_secret = $CI->config->item('client_secret');

            if ($client_id != $local_client_id || $client_secret != $local_client_secret) {
                $output['code'] = REST_Controller::HTTP_NOT_FOUND;
                $output['message'] = "Client ID or Client Secret is invalid.";
                $this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
                return;
            }

            $tokenData['timestamp'] = now();
            $output['access_token'] = AUTHORIZATION::generateToken($tokenData);
            $output['expires_in'] = $CI->config->item('token_timeout') * 60 . " seconds";
            $this->set_response($output, REST_Controller::HTTP_OK);

        }
    }

    /**
     * URL: BASE_URL/auth/logout
     * Method: POST
     */
    public function logout_post()
    {
        $output['access_token'] = null;
        $this->set_response($output, REST_Controller::HTTP_OK);
    }
}