<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$mod_lang = "UNITPAY_";
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
		'Typepay' => array(
			"NAME" => Loc::getMessage($mod_lang.'Typepay'),
			'DESCRIPTION' => Loc::getMessage($mod_lang.'DESC_Typepay'),
			'SORT' => 340,
			'GROUP' => Loc::getMessage($mod_lang.'ORDER_INFO'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'INPUT',
				'PROVIDER_VALUE' => 'default'
			),
			"TYPE" => "SELECT",
			'INPUT' => array(
				'TYPE' => 'ENUM',
				'OPTIONS' => array(
					"default"	=> 	Loc::getMessage($mod_lang.'PAYTYPE_default'),
					"any"		=>	Loc::getMessage($mod_lang.'PAYTYPE_any'),
					"mc"		=>	Loc::getMessage($mod_lang.'PAYTYPE_mc'),
					"sms"		=>	Loc::getMessage($mod_lang.'PAYTYPE_sms'),
					"card"		=>	Loc::getMessage($mod_lang.'PAYTYPE_card'),
					"webmoney"	=>	Loc::getMessage($mod_lang.'PAYTYPE_webmoney'),
					"yandex"	=>	Loc::getMessage($mod_lang.'PAYTYPE_yandex'),
					"qiwi"		=>	Loc::getMessage($mod_lang.'PAYTYPE_qiwi'),
					"paypal"	=>	Loc::getMessage($mod_lang.'PAYTYPE_paypal'),
					"alfaClick"	=>	Loc::getMessage($mod_lang.'PAYTYPE_alfaClick'),
					"cash"		=>	Loc::getMessage($mod_lang.'PAYTYPE_cash'),
				),
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
