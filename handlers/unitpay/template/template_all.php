<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<form action="<?=$params["url"]?>" method="post" target="_blank" accept-charset="utf-8">
	<input type="hidden" name="account" value="<?=$params["account"]?>">
    <input type="hidden" name="sum" value="<?=$params["sum"]?>">
    <input type="hidden" name="currency" value="<?=$params["currency"]?>">
	<?if(strlen($params["locale"])>0):?>
		<input type="hidden" name="locale" value="<?=$params["locale"]?>">
	<?endif?>
    <input type="hidden" name="desc" value="<?=$params["desc"]?>">
	<?if(strlen($params["signature"])>0):?>
		<input type="hidden" name="signature" value="<?=$params["signature"]?>">
	<?endif?>
    <input type="submit" value="<?=GetMessage("CZ_UNITPAY_BTN_PAY")?>">
</form>