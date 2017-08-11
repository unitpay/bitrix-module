<?
$pathInstall = str_replace('\\', '/', __FILE__);
$pathInstall = substr($pathInstall, 0, strlen($pathInstall) - strlen('/index.php'));

IncludeModuleLangFile($pathInstall . '/install.php');

class czebra_unitpay extends CModule
{
    var $MODULE_ID = "czebra.unitpay";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
	var $MODULE_GROUP_RIGHTS = 'Y';

    function czebra_unitpay(){
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)){
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
		$this->MODULE_NAME = GetMessage('CZ_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('CZ_MODULE_DESCRIPTION');

		$this->PARTNER_NAME = GetMessage('DEVELOPER');
		$this->PARTNER_URI = GetMessage('DEVELOPER_SITE');
    }

    function DoInstall(){		
		global $APPLICATION;			
		$this->InstallFiles();
		RegisterModule($this->MODULE_ID);		
		return true;
    }
	
	function InstallFiles() {
		$isConverted = \Bitrix\Main\Config\Option::get('main', '~sale_converted_15', "N"); 
		if(defined("SM_VERSION") && version_compare(SM_VERSION, "16.0.0") >= 0 && $isConverted == "Y"){
			CopyDirFiles(
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/payment_new',
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/sale_payment/', true, true );
			CopyDirFiles(
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/unitpay_processor_new',
				$_SERVER['DOCUMENT_ROOT'] , true, true );
		}
		else{
			CopyDirFiles(
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/payment',
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/sale_payment/', true, true );
			CopyDirFiles(
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/unitpay_processor',
				$_SERVER['DOCUMENT_ROOT'] , true, true );
		}
		return true;
	}
	
    function DoUninstall(){		
		global $APPLICATION;
		$this->UnInstallFiles();
		COption::RemoveOption($this->MODULE_ID);
		UnRegisterModule($this->MODULE_ID);
		return true;
    }
	
	function UnInstallFiles($arParams = array()){
		DeleteDirFilesEx("/bitrix/php_interface/include/sale_payment/".$this->MODULE_ID);
		DeleteDirFilesEx("/bitrix/php_interface/include/sale_payment/czebra_unitpay");
		DeleteDirFilesEx("/unitpay_processor");
		return true;
	}

	function GetModuleRightList()
	{
		global $MESS;
		$arr = array(
			"reference_id" => array("D","W"),
			"reference" => array(
				"[D] ".GetMessage("CZEBRA_D"),
				"[W] ".GetMessage("CZEBRA_W"))
			);
		return $arr;
	}
}
?>