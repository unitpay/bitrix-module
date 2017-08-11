<?
$module_id = "czebra.unitpay";

if (!$USER->CanDoOperation('czebra.unitpay_settings'))
{
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

CModule::IncludeModule("iblock");
CModule::IncludeModule('czebra.unitpay');

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
	Array("numb_", GetMessage('CZ_numb'), array("text"), "",GetMessage('CZ_D_numb')),	
	Array("pkey_", GetMessage('CZ_pkey'), array("text"), "",GetMessage('CZ_D_pkey')),	
	Array("skey_", GetMessage('CZ_skey'), array("text"), "",GetMessage('CZ_D_skey')),	
	Array("desc_", GetMessage('CZ_desc'), array("text"), "",GetMessage('CZ_D_desc')),
	Array("purseType_", GetMessage('CZ_purseType'), array("text"), "",GetMessage('CZ_D_purseType')),	
	
	//Array("curr_", GetMessage('CZ_curr'), array("text"), ""),	
	//Array("lang_", GetMessage('CZ_lang'), array("text"), ""),	
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage('CZ_PROP'), "ICON" => "unitpay_settings", "TITLE" => GetMessage('CZ_PROP_TITLE')),
	array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "unitpay_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);


$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid())
{
	if (strlen($RestoreDefaults) > 0)
	{
		COption::RemoveOption('czebra.unitpay');
		$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
	}
	else{
		foreach($siteList as $site)
		{
			COption::SetOptionString('czebra.unitpay', 'numb_'.$site['ID'], 
												$_POST['numb_'.$site['ID']]);	
			COption::SetOptionString('czebra.unitpay', 'pkey_'.$site['ID'], 
												$_POST['pkey_'.$site['ID']]);	
			COption::SetOptionString('czebra.unitpay', 'skey_'.$site['ID'], 
												$_POST['skey_'.$site['ID']]);	
			COption::SetOptionString('czebra.unitpay', 'desc_'.$site['ID'], 
												$_POST['desc_'.$site['ID']]);	
			COption::SetOptionString('czebra.unitpay', 'curr_'.$site['ID'], 
												$_POST['curr_'.$site['ID']]);	
			COption::SetOptionString('czebra.unitpay', 'lang_'.$site['ID'], 
												$_POST['lang_'.$site['ID']]);	
			
			COption::SetOptionString('czebra.unitpay', 'purseType_'.$site['ID'], 
												$_POST['purseType_'.$site['ID']]);
			
			COption::SetOptionString('czebra.unitpay', 'typepay_'.$site['ID'], 
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
	$val = COption::GetOptionString("czebra.unitpay", $arOption[0].$site['ID'], $arOption[3]);
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
		?><input type="checkbox" name="<?echo htmlspecialcharsbx($arOption[0]).$site['ID']?>" id="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?> /><?
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
	<td><?=GetMessage('CZ_curr')?></td>
	<td>
		<?$curr = COption::GetOptionString('czebra.unitpay', 'curr_'.$site['ID'], "RUB");?>
		<select name="curr_<?=$site['ID']?>" style="width: 90%;">
			<option value="RUB" <?if($curr=="RUB"):?>selected=""<?endif?>>
				RUB (Рубль)
			</option>
			<option value="UAH" <?if($curr=="UAH"):?>selected=""<?endif?>>
				UAH (Гривна)
			</option>
			<option value="BYR" <?if($curr=="BYR"):?>selected=""<?endif?>>
				BYR (Белорусский рубль)
			</option>
			<option value="EUR" <?if($curr=="EUR"):?>selected=""<?endif?>>
				EUR (Евро)
			</option>
			<option value="USD" <?if($curr=="USD"):?>selected=""<?endif?>>
				USD (Доллар США)
			</option>
		</select>
		<p><small><?=GetMessage('CZ_D_curr')?></small></p>
	</td>
</tr>
<tr>
	<td><?=GetMessage('CZ_lang')?></td>
	<td>
		<?$lang = COption::GetOptionString('czebra.unitpay', 'lang_'.$site['ID'], "ru");?>
		<select name="lang_<?=$site['ID']?>" style="width: 90%;">
			<option value="ru" <?if($lang=="ru"):?>selected=""<?endif?>>Русский</option>
			<option value="en" <?if($lang=="en"):?>selected=""<?endif?>>Английский</option>
		</select>
		<p><small><?=GetMessage('CZ_D_lang')?></small></p>
	</td>
</tr>


<tr>
	<td><?=GetMessage('CZ_typepay')?></td>
	<td>
		<?$typepay = COption::GetOptionString('czebra.unitpay', 'typepay_'.$site['ID'], "");?>
		<select name="typepay_<?=$site['ID']?>" style="width: 90%;">
			<option value="" <?if($typepay==""):?>selected="selected"<?endif?>>Любые</option>
			<option value="mc" <?if($typepay=="mc"):?>selected="selected"<?endif?>>Мобильный платеж</option>
			<option value="sms" <?if($typepay=="sms"):?>selected="selected"<?endif?>>SMS-оплата</option>
			<option value="card" <?if($typepay=="card"):?>selected="selected"<?endif?>>Пластиковые карты</option>
			<option value="webmoney" <?if($typepay=="webmoney"):?>selected="selected"<?endif?>>WebMoney</option>
			<option value="yandex" <?if($typepay=="yandex"):?>selected="selected"<?endif?>>Яндекс.Деньги</option>
			<option value="qiwi" <?if($typepay=="qiwi"):?>selected="selected"<?endif?>>Qiwi</option>
			<option value="paypal" <?if($typepay=="paypal"):?>selected="selected"<?endif?>>PayPal</option>
			<option value="alfaClick" <?if($typepay=="alfaClick"):?>selected="selected"<?endif?>>Альфа-Клик</option>
			<option value="cash" <?if($typepay=="cash"):?>selected="selected"<?endif?>>Наличные</option>
		</select>
		<p><small><?=GetMessage('CZ_D_typepay')?></small></p>
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
