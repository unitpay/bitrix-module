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
					"default"	=> 	"По-умолчанию (из настроек модуля)",
					"any"		=>	"Любые",
					"mc"		=>	"Мобильный платеж",
					"sms"		=>	"SMS-оплата",
					"card"		=>	"Пластиковые карты",
					"webmoney"	=>	"WebMoney",
					"yandex"	=>	"Яндекс.Деньги",
					"qiwi"		=>	"Qiwi",
					"paypal"	=>	"PayPal",
					"alfaClick"	=>	"Альфа-Клик",
					"cash"		=>	"Наличные",
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
