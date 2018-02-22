<?php
class Product {

	protected $table = "products";

	function __construct() {

	}

	function removeProduct($idProduct) {
		global $db;

		$array_update = array("status" => -1);
		$array_where = array("id" => $idProduct);
		$array_symbol = array("=");

		//$db->Delete($this->table,$array_where,$array_symbol);
		$db->Update($this->table,$array_update,$array_where,$array_symbol);

		return array("ok" => 1);
	}

	function removeAll() {
		global $db;

		$getAll = $db->truncate($this->table);

		return array("ok" => 1);
	}

	function getAll() {
		global $db;

		$getAll = $db->get_results("SELECT * FROM ".$this->table." WHERE status=:status",array(":status" => 1));

		if( is_array($getAll) && count($getAll) > 0 )
			$result = $getAll;
		else
			$result = array('error' => '1', 'message' => 'empty result');

		return $result;
	}

	function getProduct($idProduct) {
		global $db;

		$array_fields = array("id","name","price","date_created");
		$array_where = array("id" => $idProduct);
		$array_symbol = array("=");

		$getProduct = $db->get_row($this->table,$array_fields,$array_where,$array_symbol);

		if( is_array($getProduct) && count($getProduct) > 0 )
			$result = $getProduct;
		else
			$result = array('error' => '1', 'message' => 'empty result');

		return $result;
	}


	function saveFromJson($objProduct) {

		global $db;
		
		$array_insert = array(
								"id"	=> $objProduct->id,
								"name"	=> $objProduct->name,
								"price"	=> $objProduct->price
							);

		$returnID = $db->Insert($this->table,$array_insert);

		return $returnID;

	}

}
?>