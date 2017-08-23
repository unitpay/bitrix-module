<?
use \Bitrix\Main\Application;

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

    //print_r($result);

    if($result->isSuccess()){
		$method = $request->get('method');
        if ($method  == 'check'){
        	echo json_encode(array("result" => array("message" => 'Check Success')));
        }
        elseif ($method == 'pay'){
        	echo json_encode(array("result" => array("message" => 'Pay Success')));
        }
        else{
			echo json_encode(array("result" => array("error" => 'No connect')));
		}
	}
	else{
		$message = implode(";", $result->getErrorMessages());
                if (strtoupper(SITE_CHARSET) != 'UTF-8') {
                        $message = \Bitrix\Main\Text\Encoding::convertEncodingArray($message, SITE_CHARSET, "UTF-8");
                }
		echo json_encode(array("result" => array("error" => $message)));
	}
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
