<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);?>

<?if(array_key_exists("error", $params)):?>
	<?
		$mess = $params["error"]["message"];
		if (strtoupper(SITE_CHARSET) != 'UTF-8') {
                        $mess = \Bitrix\Main\Text\Encoding::convertEncodingArray($mess, "UTF-8", SITE_CHARSET);
                }

	?>
	<p><?=GetMessage("UNITPAY_ERROR").": ".$mess?></p>
<?else:?>
	<?if($params["result"]["type"] == "redirect"):?>
		<form action="<?=$params["result"]["redirectUrl"]?>" method="post" target="_blank" accept-charset="utf-8">
		<input type="submit" value="<?=GetMessage("UNITPAY_BTN_PAY")?>">
		</form>
	<?elseif($params["result"]["type"] == "invoice"):?>
		<form action="<?=$params["result"]["receiptUrl"]?>" method="post" target="_blank" accept-charset="utf-8">
    		<input type="submit" value="<?=GetMessage("UNITPAY_BTN_PAY_INVOICE")?>">
		</form>
	<?endif?>
<?endif?>
