<?php

namespace Kidino\Billplz;

class Billplz {

	private $host = 'https://www.billplz.com/api/v3';
	private $data = array();
	private $api_key = '';
	private $ch;
	private $sep = '/';
	
	private $end_bills = 'bills';
	private $end_collections = 'collections';

	function __construct( $data = array() ){
		if (is_array($data) && (count($data) > 0)) {
			if (isset($data['api_key'])) $this->api_key = $data['api_key'];
			if (isset($data['host'])) $this->host = $data['host'];
		}
	}
	
	function create_collection(){
		$this->ch = curl_init($this->host . $this->sep . $this->end_collections);

		if (isset($this->data['logo'])) {

			if (!file_exists($this->data['logo'])) {

				$this->error = "logo file not found";
				return false;

			}
			// check for class CurlFile exist or not
			// as for php version of 5.6 >, the option CURLOPT_SAFE_UPLOAD
			// was set to true, and below than that is false
			// true means, its prevent @ prefix from working for security reason
			// to handler this problem, as of php start from 5.6 >, need to use
			// CurlFile class for file uploading together with 
			// CURLOPT_SAFE_UPLOAD which set to true.
			// ------------------------------------------
			// As PHP version below than mentioned version above, just fallback to the original method
			// which is by using @ prefix and set 
			// this opt CURLOPT_SAFE_UPLOAD to false(optional as the default value is false)
			if ( class_exists('CurlFile', false ) ) {
				// getting mime type for uploaded files
				$finfo = finfo_open( FILEINFO_MIME_TYPE );
				$mimeType = finfo_file($finfo, $this->data['logo']);
				finfo_close($finfo);
				$fileUpload = new \CURLFile( $this->data['logo'], $mimeType,'logo' );
				$this->data['logo'] = $fileUpload;

			}
			else {
				// fallback to the original method
				$this->data['logo'] = '@'.$this->data['logo'];
				curl_setopt( $this->ch, CURLOPT_SAFE_UPLOAD, FALSE );

			}
		}

		return $this->_run();
	}

	function create_bill(){
		$this->ch = curl_init($this->host . $this->sep . $this->end_bills);
		return $this->_run();
	}

	function get_bill( $bill_id ){
		$this->ch = curl_init($this->host . $this->sep . $this->end_bills . $this->sep . $bill_id);
		return $this->_run();
	}

	function delete_bill( $bill_id ){
		$this->ch = curl_init($this->host . $this->sep . $this->end_bills . $this->sep . $bill_id);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		return $this->_run();
	}

	function set_data($data, $data2 = null) {
		if (is_array($data)) {
			foreach($data as $key => $value){
				$this->data[$key] = $value;
			}
		} else if ($data2 !== null) {
			$this->data[$data] = $data2;
		}
	}

	function _run(){
		
        if ($this->api_key == '') {
            $this->error = 'API key was not set';
            return false;
        }        

		curl_setopt($this->ch, CURLOPT_HEADER, 1);
		curl_setopt($this->ch, CURLOPT_USERPWD, $this->api_key . ":");
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);

		if (count($this->data) > 0) {
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data );
		}

		$r = curl_exec($this->ch);
		curl_close($this->ch);
		return $r;
	}
}
