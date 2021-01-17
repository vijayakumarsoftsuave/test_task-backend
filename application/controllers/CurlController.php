<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CurlController extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function curPostRequest()
	{
		/* Endpoint */
		$url = 'http://localhost/api/insert';

		/* eCurl */
		$curl = curl_init($url);

		/* Data */
		$data = array(
			'name'=>'test',
			'email'=>'test@yahoo.com'
		);

		/* Set JSON data to POST */
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		/* Define content type */
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		/* Return json */
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		/* make request */
		$result = curl_exec($curl);

		/* close curl */
		curl_close($curl);
	}
}
