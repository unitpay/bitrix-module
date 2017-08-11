<?
	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS", true);
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));

	class UnitPayMessage
	{
		function getMessage($message)
		{
			return json_encode(array(
				"result" => array(
					"message" => $message
				)
			));
		}

		function getError($message)
		{
			return json_encode(array(
				"error" => array(
					"message" => $message
				)
			));
		}
	}

	try {

		list($method, $params) = array($_GET['method'], $_GET['params']);


		$id = $params["account"];
		if(CModule::IncludeModule('sale')){
			$arOrder = CSaleOrder::GetByID($id);
			$sum = $arOrder["PRICE"];

			$currency = COption::GetOptionString('czebra.unitpay', 'curr_'.$arOrder["LID"], '');
			if(strlen($currency)==0)
				$currency = "RUB";
			$projectId = COption::GetOptionString('czebra.unitpay', 'numb_'.$arOrder["LID"], '');

			if ((double)$params['orderSum'] != (double)$sum ||
				$params['orderCurrency'] != $currency ||
				$params['account'] != $id ||
				$params['projectId'] != $projectId)
			{
				throw new InvalidArgumentException('Order validation Error!');
			}


			if ($method  == 'check')  { print UnitPayMessage::getMessage('Check Success'); }
			elseif ($method == 'pay') {
				CSaleOrder::PayOrder((int)$id, "Y");
				print UnitPayMessage::getMessage('Pay Success');

			}
		}

	} catch (Exception $e) {
    print UnitPayMessage::getError($e->getMessage());
}


?>
