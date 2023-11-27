<?php
function myErrorHandler($code, $message, $file, $line) {
		include_once("error.php");
		http_response_code(500);
		echo 'Sorry, an error happened. Please try again later';
		error_log("Error $code : $message in $file on line $line", 0);
		die();
}
?>