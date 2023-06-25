<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
class Twitter extends CI_Controller {


public function loginview() 
 {
      $this->load->view('login_2');
}
    public function login()
    {
        // Load the TwitterOAuth library
      //   $this->load->library('twitteroauth');

        // Set your Twitter API credentials
        $consumer_key = '8gHDTZnzdfZdraNsHHFtp3uuP';
        $consumer_secret = 'A45F7gv0sqeQkaoK3lORt4mTCa22v06wkME7BLRzirY9xJwZ4r';
        $callback_url = 'http://localhost/twitter/index.php/callback';

        // Create a new TwitterOAuth instance
        $connection = new TwitterOAuth($consumer_key, $consumer_secret);

        // Get temporary credentials for OAuth
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $callback_url));

        // Save the temporary credentials to session for later use
        $this->session->set_userdata('oauth_token', $request_token['oauth_token']);
        $this->session->set_userdata('oauth_token_secret', $request_token['oauth_token_secret']);

        // Generate the Twitter login URL
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        // Redirect the user to the Twitter login page
        redirect($url);
    }

    public function callback()
    {
        // Load the TwitterOAuth library
        $this->load->library('twitteroauth');

        // Set your Twitter API credentials
        $consumer_key = '8gHDTZnzdfZdraNsHHFtp3uuP';
        $consumer_secret = 'A45F7gv0sqeQkaoK3lORt4mTCa22v06wkME7BLRzirY9xJwZ4r';

        // Get the saved temporary credentials from session
        $oauth_token = $this->session->userdata('oauth_token');
        $oauth_token_secret = $this->session->userdata('oauth_token_secret');

        // Create a new TwitterOAuth instance
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

        // Get the access token using the verifier
        $access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $this->input->get('oauth_verifier')));

        // Use the access token to authenticate the user
        $user_connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user_data = $user_connection->get('account/verify_credentials');

        print_r($user_data);
        exit;

        // Now you have the user data, you can process it as needed
        // For example, you can store the user data in the database or log the user in

        // Redirect the user to the desired page after successful login
        redirect('login');
    }

}
