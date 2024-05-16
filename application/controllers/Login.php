<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller {

  public function __construct() {
    parent::__construct();
    header('Access-Control-Allow-Origin: *'); // Change * to your allowed domain
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
     // Handle preflight request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      // Handle the preflight request
      header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
      header('Access-Control-Allow-Headers: Content-Type, Authorization');
      exit;
    } 
    // Load User_model
    $this->load->model('LoginModel');
    
    // Check if loading was successful (optional)
    if ($this->load->is_loaded('LoginModel')) {
        error_log("Login_model is loaded properly.");
    } else {
        error_log("Login_model failed to load.");  // More specific error message
    }
}




  public function index() {
    #$this->load->view('login_view'); // Load the login view
  }

  public function process() {
     // Get JSON data sent from frontend
    $data = json_decode(file_get_contents('php://input'), true);
    // Extract username and password from JSON data
    $username = isset($data['username']) ? $data['username'] : '';
    $password = isset($data['password']) ? $data['password'] : '';

    error_log("Username: " . $username . ", Password: " . $password); // Log the form data

    // Load the Login model
    $this->load->model('LoginModel');

    // Validate credentials
    if ($this->LoginModel->verifyCredentials($username, $password)) {
      // Login successful
      $response = array('message' => 'Login successful','userId' => $this->LoginModel->get_userId($username),'userName' => $username);
      $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($response));
  } else {
      // Invalid credentials
      $response = array('error' => 'Invalid username or password.');
      $this->output
          ->set_content_type('application/json')
          ->set_status_header(401) // Unauthorized
          ->set_output(json_encode($response));
  }
  }
}
