<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
include(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));

	$id =  CSalePaySystemAction::GetParamValue("OrderID");
	$sum =  CSalePaySystemAction::GetParamValue("OrderSum");

	$desc =  COption::GetOptionString('unitpay.paymodule', 'desc_'.SITE_ID, '');
	$url =  COption::GetOptionString('unitpay.paymodule', 'pkey_'.SITE_ID, '');
	$currency =  COption::GetOptionString('unitpay.paymodule', 'curr_'.SITE_ID, '');
	if(strlen($currency)==0)
		$currency = "RUB";
	$locale =  COption::GetOptionString('unitpay.paymodule', 'lang_'.SITE_ID, '');
    $domain =  COption::GetOptionString('unitpay.paymodule', 'domain_'.SITE_ID, '');
	$SecretKey = COption::GetOptionString('unitpay.paymodule', 'skey_'.SITE_ID, '');
	$typepay = COption::GetOptionString('unitpay.paymodule', 'typepay_'.SITE_ID, "");

?>

<form action="https://<?=$domain?>/pay/<?=$url?>" method="post" target="_blank" accept-charset="utf-8">
	<input type="hidden" name="account" value="<?=$id?>">
    <input type="hidden" name="sum" value="<?=$sum?>">
    <input type="hidden" name="currency" value="<?=$currency?>">
	<?if(strlen($locale) > 0):?>
		<input type="hidden" name="locale" value="<?=$locale?>">
	<?endif?>
    <input type="hidden" name="desc" value="<?=$desc?>">
    <?if(strlen($typepay) > 0):?>
    	<input type="hidden" name="paymentType" value="<?=$typepay?>">
    <?endif?>
	<?if(strlen($SecretKey) > 0):?>
		<?if(mb_detect_encoding($desc,"UTF-8, Windows-1251") == "UTF-8"):?>
			<input type="hidden" name="signature" value="<?=hash('sha256', $id.'{up}'.$currency.'{up}'.$desc.'{up}'.$sum.'{up}'.$SecretKey)?>">
		<?else:?>
			<input type="hidden" name="signature" value="<?=hash('sha256', $id.'{up}'.$currency.iconv('Windows-1251', 'UTF-8',$desc).'{up}'.$sum.'{up}'.$SecretKey)?>">
		<?endif;?>
	<?endif?>
    <input type="submit" value="<?=GetMessage("BTN_PAY")?>">
</form>
