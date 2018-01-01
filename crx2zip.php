<?php
function getCrxLogo($file,$path){
	$file=file_get_contents($file);
	
	if (ord($file[0]) === 80 && ord($file[1]) === 75 && ord($file[2]) === 3 && ord($file[3]) === 4) {
		print('This is actually a zip file');
		return false;
	}
	// 43 72 32 34 (Cr24)
	if (ord($file[0]) !== 67 || ord($file[1]) !== 114 || ord($file[2]) !== 50 || ord($file[3]) !== 52) {
		print("Invalid header: Does not start with Cr24");
		return false;
	}
	// 02 00 00 00
	if (ord($file[4]) !== 2 || ord($file[5]) || ord($file[6]) || ord($file[7])) {
		print("Unexpected crx format version number.");
		return false;
	}
	$publicKeyLength = calcLength(ord($file[8]), ord($file[9]), ord($file[10]), ord($file[11]));
	$signatureLength = calcLength(ord($file[12]), ord($file[13]), ord($file[14]), ord($file[15]));
	
	// 16 = Magic number (4), CRX format version (4), lengths (2x4)
	$zipStartOffset = 16 + $publicKeyLength + $signatureLength;
	$final_file = substr($file,$zipStartOffset);
	
	file_put_contents($path,$final_file);
}
function calcLength($a, $b, $c, $d) {
	$length = 0;

	$length += $a;
	$length += $b << 8;
	$length += $c << 16;
	$length += $d << 24;
	return $length;
}
?>