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
	<?if(isset($params["customerEmail"]) && strlen($params["customerEmail"])>0):?>
		<input type="hidden" name="customerEmail" value="<?=$params["customerEmail"]?>">
	<?endif?>
	<?if(isset($params["customerPhone"]) && strlen($params["customerPhone"])>0):?>
		<input type="hidden" name="customerPhone" value="<?=$params["customerPhone"]?>">
	<?endif?>
	<?if(isset($params["cashItems"]) && strlen($params["cashItems"])>0):?>
		<input type="hidden" name="cashItems" value="<?=$params["cashItems"]?>">
	<?endif?>
	<?if(isset($params["hideMenu"])):?>
		<input type="hidden" name="hideMenu" value="true">
	<?endif?>
	<?if(isset($params["hideOtherMethods"])):?>
		<input type="hidden" name="hideOtherMethods" value="true">
	<?endif?>
	<?if(isset($params["hideOtherPSMethods"])):?>
		<input type="hidden" name="hideOtherPSMethods" value="true">
	<?endif?>
    <input type="submit" value="<?=GetMessage("UNITPAY_BTN_PAY")?>">
</form>
