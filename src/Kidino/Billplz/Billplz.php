<?php

namespace Kidino\Billplz;

class Billplz {

	private $host = 'https://www.billplz.com/api/v2';
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
			if (file_exists($this->data['logo'])) {
				$this->data['logo'] = '@'. $this->data['logo'];
			} else {
				$this->error = "logo file not found";
				return false;
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
