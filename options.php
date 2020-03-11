<?
$module_id = "unitpay.paymodule";

if (!$USER->CanDoOperation('unitpay.paymodule_settings'))
{
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

CModule::IncludeModule("iblock");
CModule::IncludeModule('unitpay.paymodule');

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

	use Bitrix\Main\SiteTable;
	$siteList = array();
	$siteIterator = SiteTable::getList(array(
		'select' => array('LID', 'NAME'),
		'order' => array('SORT' => 'ASC')
	));
	while ($oneSite = $siteIterator->fetch())
	{
		$siteList[] = array('ID' => $oneSite['LID'], 'NAME' => $oneSite['NAME']);
	}
	unset($oneSite, $siteIterator);
	$siteCount = count($siteList);

$arAllOptions = Array(
    Array("domain_", GetMessage('UNITPAY_domain'), array("text"), "",GetMessage('UNITPAY_D_domain')),
	Array("numb_", GetMessage('UNITPAY_numb'), array("text"), "",GetMessage('UNITPAY_D_numb')),
	Array("pkey_", GetMessage('UNITPAY_pkey'), array("text"), "",GetMessage('UNITPAY_D_pkey')),
	Array("skey_", GetMessage('UNITPAY_skey'), array("text"), "",GetMessage('UNITPAY_D_skey')),
	Array("desc_", GetMessage('UNITPAY_desc'), array("text"), "",GetMessage('UNITPAY_D_desc')),
	Array("purseType_", GetMessage('UNITPAY_purseType'), array("text"), "",GetMessage('UNITPAY_D_purseType')),
	Array("cashboxItems_", GetMessage('UNITPAY_cashboxItems'), array("checkbox"), "",GetMessage('UNITPAY_D_cashboxItems')),

	//Array("curr_", GetMessage('UNITPAY_curr'), array("text"), ""),
	//Array("lang_", GetMessage('UNITPAY_lang'), array("text"), ""),
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage('UNITPAY_PROP'), "ICON" => "unitpay_settings", "TITLE" => GetMessage('UNITPAY_PROP_TITLE')),
	array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "unitpay_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);


$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
{
	if (strlen($RestoreDefaults) > 0)
	{
		COption::RemoveOption('unitpay.paymodule');
		$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
	}
	else{
		foreach($siteList as $site)
		{
            COption::SetOptionString('unitpay.paymodule', 'domain_'.$site['ID'],
                                                $_POST['domain_'.$site['ID']]);
			COption::SetOptionString('unitpay.paymodule', 'numb_'.$site['ID'],
												$_POST['numb_'.$site['ID']]);
			COption::SetOptionString('unitpay.paymodule', 'pkey_'.$site['ID'],
												$_POST['pkey_'.$site['ID']]);
			COption::SetOptionString('unitpay.paymodule', 'skey_'.$site['ID'],
												$_POST['skey_'.$site['ID']]);
			COption::SetOptionString('unitpay.paymodule', 'desc_'.$site['ID'],
												$_POST['desc_'.$site['ID']]);
			COption::SetOptionString('unitpay.paymodule', 'curr_'.$site['ID'],
												$_POST['curr_'.$site['ID']]);
			COption::SetOptionString('unitpay.paymodule', 'lang_'.$site['ID'],
												$_POST['lang_'.$site['ID']]);

			COption::SetOptionString('unitpay.paymodule', 'purseType_'.$site['ID'],
												$_POST['purseType_'.$site['ID']]);

			COption::SetOptionString('unitpay.paymodule', 'cashboxItems_'.$site['ID'],
												$_POST['cashboxItems_'.$site['ID']]);

			COption::SetOptionString('unitpay.paymodule', 'typepay_'.$site['ID'],
												$_POST['typepay_'.$site['ID']]);
		}
	}
}

$tabControl->Begin();
?>
<form
	method="POST"  enctype="multipart/form-data"
	action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?echo LANG?>"
	name="antispampro_settings">
<?=bitrix_sessid_post();?>
<?
$tabControl->BeginNextTab();?>

<td colspan="2" align="center">
		<table cellspacing="0" cellpadding="0" border="0" class="internal" style="width: 85%;">
<?
foreach($siteList as $site)
		{
		?>
			<tr colspan="2" class="heading">
				<td valign="top">
					[<a href="site_edit.php?LID=<?=$site["ID"]?>&lang=<?=LANGUAGE_ID?>" title="<?=GetMessage("SALE_SITE_ALT")?>"><?echo $site["ID"] ?></a>] <?echo ($site["NAME"]) ?>
				</td>
			</tr>
			<tr colspan="2">
				<td>
					<table style="width: 100%;">
<?
foreach($arAllOptions as $arOption):
	$val = COption::GetOptionString("unitpay.paymodule", $arOption[0].$site['ID'], $arOption[3]);
	$type = $arOption[2];

?>
<tr>
	<td valign="top" width="25%" style="vertical-align: middle;"><?
	if ($type[0] == "checkbox")
		echo "<label for=\"".htmlspecialcharsbx($arOption[0])."\">".$arOption[1]."</label>";
	else
		echo $arOption[1];
?>: </td>
	<td valign="top" width="70%"><?
	if($type[0]=="checkbox"):
		?><input type="checkbox" name="<?echo htmlspecialcharsbx($arOption[0]).$site['ID']?>" id="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?> /><p><small><?=$arOption[4]?></small></p><?
	elseif ($type[0]=="text"):
		?><input type="text" size="<?echo $type[1]?>" maxlength="2550" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0]).$site['ID']?>" style="width:90%;" />
		<p><small><?=$arOption[4]?></small></p>
		<?
	elseif($type[0]=="textarea"):
		?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0]).$site['ID']?>" style="width:90%;"><?echo htmlspecialcharsbx($val)?></textarea><?
	endif;
	?></td>

</tr>
<?endforeach;?>
<tr>
	<td><?=GetMessage('UNITPAY_curr')?></td>
	<td>
		<?$curr = COption::GetOptionString('unitpay.paymodule', 'curr_'.$site['ID'], "RUB");?>
		<select name="curr_<?=$site['ID']?>" style="width: 90%;">
			<option value="RUB" <?if($curr=="RUB"):?>selected=""<?endif?>>
				<?=GetMessage('UNITPAY_CURRENCY_RUB'); ?>
			</option>
			<option value="UAH" <?if($curr=="UAH"):?>selected=""<?endif?>>
				<?=GetMessage('UNITPAY_CURRENCY_UAH'); ?>
			</option>
			<option value="BYR" <?if($curr=="BYR"):?>selected=""<?endif?>>
				<?=GetMessage('UNITPAY_CURRENCY_BYR'); ?>
			</option>
			<option value="EUR" <?if($curr=="EUR"):?>selected=""<?endif?>>
				<?=GetMessage('UNITPAY_CURRENCY_EUR'); ?>
			</option>
			<option value="USD" <?if($curr=="USD"):?>selected=""<?endif?>>
				<?=GetMessage('UNITPAY_CURRENCY_USD'); ?>
			</option>
		</select>
		<p><small><?=GetMessage('UNITPAY_D_curr')?></small></p>
	</td>
</tr>
<tr>
	<td><?=GetMessage('UNITPAY_lang')?></td>
	<td>
		<?$lang = COption::GetOptionString('unitpay.paymodule', 'lang_'.$site['ID'], "ru");?>
		<select name="lang_<?=$site['ID']?>" style="width: 90%;">
			<option value="ru" <?if($lang=="ru"):?>selected=""<?endif?>><?=GetMessage('UNITPAY_LANG_RU'); ?></option>
			<option value="en" <?if($lang=="en"):?>selected=""<?endif?>><?=GetMessage('UNITPAY_LANG_EN'); ?></option>
		</select>
		<p><small><?=GetMessage('UNITPAY_D_lang')?></small></p>
	</td>
</tr>


<tr>
	<td><?=GetMessage('UNITPAY_typepay')?></td>
	<td>
		<?$typepay = COption::GetOptionString('unitpay.paymodule', 'typepay_'.$site['ID'], "");?>
		<select name="typepay_<?=$site['ID']?>" style="width: 90%;">
			<option value="" <?if($typepay==""):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_any'); ?></option>
			<option value="mc" <?if($typepay=="mc"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_mc'); ?></option>
			<option value="sms" <?if($typepay=="sms"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_sms'); ?></option>
			<option value="card" <?if($typepay=="card"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_card'); ?></option>
			<option value="webmoney" <?if($typepay=="webmoney"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_webmoney'); ?></option>
			<option value="yandex" <?if($typepay=="yandex"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_yandex'); ?></option>
			<option value="qiwi" <?if($typepay=="qiwi"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_qiwi'); ?></option>
			<option value="paypal" <?if($typepay=="paypal"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_paypal'); ?></option>
			<option value="alfaClick" <?if($typepay=="alfaClick"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_alfaClick'); ?></option>
			<option value="cash" <?if($typepay=="cash"):?>selected="selected"<?endif?>><?=GetMessage('UNITPAY_PAYTYPE_cash'); ?></option>
		</select>
		<p><small><?=GetMessage('UNITPAY_D_typepay')?></small></p>
	</td>
</tr>

</table>
		</td>
	</tr>

		<?}?>

</table>
		</td>

<?
$tabControl->BeginNextTab();

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");

$tabControl->Buttons();?>
<script language="JavaScript">
function confirmRestoreDefaults()
{
	return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>');
}
</script>
<input type="submit" name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirmRestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
<?$tabControl->End();?>
</form>
