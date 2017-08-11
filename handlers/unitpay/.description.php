<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$mod_lang = "CZ_UNITPAY_";
$data = array(
	'NAME' => Loc::getMessage($mod_lang.'NamePayment1'),
	'SORT' => 500,
	'CODES' => array(
		'OrderID' => array(
			"NAME" => Loc::getMessage($mod_lang.'OrderID'),
			'SORT' => 300,
			'GROUP' => Loc::getMessage($mod_lang.'ORDER_INFO'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'PAYMENT',
				'PROVIDER_VALUE' => 'ID'
			)
		),
		'OrderSum' => array(
			"NAME" => Loc::getMessage($mod_lang.'OrderSum'),
			'SORT' => 325,
			'GROUP' => Loc::getMessage($mod_lang.'ORDER_INFO'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'PAYMENT',
				'PROVIDER_VALUE' => 'SUM'
			)
		),
        
        'Telefon' => array(
			"NAME" => Loc::getMessage($mod_lang.'Telefon'),
            'DESCRIPTION' => Loc::getMessage($mod_lang.'DESC_Telefon'),
			'SORT' => 350,
			'GROUP' => Loc::getMessage($mod_lang.'ORDER_INFO'),
		),
        'Operator' => array(
			"NAME" => Loc::getMessage($mod_lang.'Operator'),
            'DESCRIPTION' => Loc::getMessage($mod_lang.'DESC_Operator'),
			'SORT' => 350,
			'GROUP' => Loc::getMessage($mod_lang.'ORDER_INFO'),
		),
	)
);