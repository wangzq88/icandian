<?php
require_once Yii::app ()->basePath . '/extensions/rsa/rsa.php';

function convert($hexString) {
	$hexLenght = strlen ( $hexString );
	// only hex numbers is allowed 
	if ($hexLenght % 2 != 0 || preg_match ( "/[^\da-fA-F]/", $hexString ))
		return false;
	$binString = '';
	for($x = 1; $x <= $hexLenght / 2; $x ++) {
		$binString .= chr ( hexdec ( substr ( $hexString, 2 * $x - 2, 2 ) ) );
	}
	
	return $binString;
}
/**
 * RSA 解密
 *
 */
function decryptPassword($password) {
	$modulus = '124124790696783899579957666732205416556275207289308772677367395397704314099727565633927507139389670490184904760526156031441045563225987129220634807383637837918320623518532877734472159024203477820731033762885040862183213160281165618500092483026873487507336293388981515466164416989192069833140532570993394388051.0000000000';
	$private = '59940207454900542501281722336097731406274284149290386158861762508911700758780200454438527029729836453810395133453343700246367853044479311924174899432036400630350527132581124575735909908195078492323048176864577497230467497768502277772070557874686662727818507841304646138785432507752788647631021854537869399041.0000000000';
	$public = "65537";
	$keylength = "1024";
	//php encrypt create  
	//$encrypted = rsa_encrypt("vzxcvz bdxf", $public, $modulus, $keylength);
	//$str= bin2hex($encrypted);//bin data to hex data 
	

	//$str = $_POST['ciphertext'];
	//echo $str."<br>";
	$encrypted = convert ( $password ); //hex data to bin data
	

	return rsa_decrypt ( $encrypted, $private, $modulus, $keylength );
}

?>