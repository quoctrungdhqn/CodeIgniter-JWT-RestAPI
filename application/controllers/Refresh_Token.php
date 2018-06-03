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

class Refresh_Token extends REST_Controller
{

    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/refresh_token/token
     * Method: GET
     */
    public function token_post()
    {
        $tokenData = array();
        $tokenData['timestamp'] = now();

        $output['access_token'] = AUTHORIZATION::generateToken($tokenData);
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/refresh_token/token
     * Method: POST
     * Header Key: Authorization
     * Value: Auth token generated in GET call
     */
    /*public function token_get()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {
                $this->set_response($decodedToken, REST_Controller::HTTP_OK);
                return;
            }
        }

        $output['code'] = REST_Controller::HTTP_UNAUTHORIZED;
        $output['message'] = "Unauthorized. Access token is missing or invalid.";
        $this->set_response($output, REST_Controller::HTTP_UNAUTHORIZED);
    }*/
}