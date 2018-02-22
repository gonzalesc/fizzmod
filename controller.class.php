<?php
require_once dirname(__FILE__) . '/inc/database.php';
require_once dirname(__FILE__) . '/inc/product.php';

$db = new Database();

class Controller {
	protected $product;

	function __construct() {
		$this->product = new Product();

	}

	function processProducts( $urlJson = "remote.fizzmod.com/hnpyeMpw0O75okYD/backend/pub/products.json" ) {

		if( !empty($urlJson) ) {
			
			$jsonProducts = file_get_contents("http://hnpyeMpw0O75okYD:UFhDixmDNMC3kvV8qJ8Dy@".$urlJson);
			$objProducts = json_decode($jsonProducts);

			if( $objProducts ) {

				$this->product->removeAll();

				foreach($objProducts as $objProduct) {
					$returnID = $this->product->saveFromJson($objProduct);
				}
			}
		}

		return array('ok' => 1);
	}

	function dash() {

		$html->var["title"] = "Dashboard";
		$html->var["site_url"] = $this->site_url();
		$html->var["ajax_url"] = $this->site_url();

		//$products = $this->product->getAll();

		require_once $this->site_path('assets/layouts/header.php');
		require_once $this->site_path('assets/layouts/dash.php');
		require_once $this->site_path('assets/layouts/footer.php');
	}


	function ajaxProcess() {

		if( isset($_POST["ajax"]) ) {
			switch($_POST["action"]) {
				case "get_product" : $array_data = $this->product->getProduct($_POST["id"]); break;
				case "get_products" : $array_data = $this->product->getAll(); break;
				case "delete_product" : $array_data = $this->product->removeProduct($_POST["id"]); break;
				case "load_all" : $array_data = $this->processProducts(); break;
			}

			echo json_encode($array_data);
		}

		die();
	}


	function site_url( $page = "" ) {

		$domain = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];

		if( !empty($page) && $page != null )
			$domain.= "/" . $page;

		return $domain;
	}

	function site_path( $page = "" ) {
		$path = $_SERVER['DOCUMENT_ROOT'];

		if( !empty($page) && $page != null )
			$path.= "/" . $page;

		return $path;
	}
}
?>