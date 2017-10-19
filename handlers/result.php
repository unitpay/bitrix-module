<?
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
define("DisableEventsCheck", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if (CModule::IncludeModule("sale"))
{
    $context = Application::getInstance()->getContext();
    $request = $context->getRequest();

    $item = Bitrix\Sale\PaySystem\Manager::searchByRequest($request);
    $service = new Bitrix\Sale\PaySystem\Service($item);
    $result = $service->processRequest($request);

    // print_r($request);

    if($result->isSuccess()){
	$method = $request->get('method');
        if ($method  == 'check'){
        	echo json_encode(array("result" => array("message" => getMessage('UNITPAY_CHECK_SUCCESS'))));
        } elseif ($method == 'pay'){
        	echo json_encode(array("result" => array("message" => getMessage('UNITPAY_PAY_SUCCESS'))));
        } else {
		echo json_encode(array("error" => array("message" => getMessage('UNITPAY_UNKNOWN_METHOD'))));
	}
    } else {
		$message = implode(";", $result->getErrorMessages());
                if (strtoupper(SITE_CHARSET) != 'UTF-8') {
                        $message = \Bitrix\Main\Text\Encoding::convertEncodingArray($message, SITE_CHARSET, "UTF-8");
                }
		echo json_encode(array("error" => array("message" => $message)));
    }
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
