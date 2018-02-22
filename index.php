<?php
/*
	Name : Alexander Gonzales
	Web : https://vcard.gonzalesc.org
*/

	require_once dirname(__FILE__) . '/controller.class.php';
	$controller = new Controller();

	if(isset($_GET["json"])) {
		$controller->processProducts();
	} elseif( isset($_POST["ajax"]) ) {
		$controller->ajaxProcess();

	} else {
		$controller->dash();
	}
?>