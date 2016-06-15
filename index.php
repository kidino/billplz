<?php
require 'src/Kidino/Billplz/Billplz.php';
use Kidino\Billplz\Billplz;

$bplz = new Billplz(array('api_key' => '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81'));
$bplz->set_data('title','Home Tutoring');
$bplz->set_data('description','asdasd');
$bplz->set_data('logo','/opt/lampp/htdocs/billplz/bau-mulut.jpg');
$result = $bplz->create_collection();
if ( $result ) {
	print_r($result);
}
else {
	echo $bplz->errorMessage();
}


/*list($rheader, $rbody) = explode("\n\n", $result);
$bplz_result = json_decode($rbody);*/