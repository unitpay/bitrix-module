<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));

$psTitle = GetMessage("PAY_TITLE");
$psDescription = GetMessage("PAY_DESCR");

$arPSCorrespondence = array(
	"OrderID" => array(
			"NAME" => GetMessage("OrderID"),
			"DESCR" => GetMessage("OrderID_DESCR"),
			"VALUE" => "ID",
			"TYPE" => "ORDER"
		),	
	"OrderSum" => array(
			"NAME" => GetMessage("OrderSum"),
			"DESCR" => GetMessage("OrderSum_DESCR"),
			"VALUE" => "PRICE",
			"TYPE" => "ORDER"
		),	
	);
?>
