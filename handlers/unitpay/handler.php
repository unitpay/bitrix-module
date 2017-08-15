<?php
namespace Sale\Handlers\PaySystem;


use Bitrix\Main;
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Payment;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Shipment;

class unitpay_paymoduleHandler extends PaySystem\ServiceHandler
{
    public static function getIndicativeFields()
    {
        return array('UNITPAY_HANDLER' => 'UNITPAY');
    }

    protected function getUrlList()
    {
        return array(
            'pay' => array(self::ACTIVE_URL => 'https://unitpay.ru/api'),
            'pay_all' => array(self::ACTIVE_URL => 'https://unitpay.ru/pay/'),
        );
    }

    public function getPaymentIdFromRequest(Request $request)
    {
        $param = $request->get('params');
        return $param['account'];
    }

    static protected function isMyResponseExtended(Request $request, $paySystemId)
    {
        //$id = $request->get('UNITPAY_PAYSYSTEM_CODE');
        return true;//$id == $paySystemId;         
    }

    protected function isTestMode()
    {
        return 'N';
    }

    public function getCurrencyList()
    {
        return array('RUB');
    }

    public function initiatePay(Payment $payment, Request $request = null)
    {
        $order = $payment->getCollection()->getOrder();

        $userEmail = null;
        $userPhone = null;

        $properties = $order->getPropertyCollection();
        if ($properties->getUserEmail()) {
            $userEmail = $properties->getUserEmail()->getValue();
        }
        if ($properties->getPhone()) {
            $userPhone = $properties->getPhone()->getValue();
        }

        $orderItems = array();

        if (Option::get('unitpay.paymodule', 'cashboxItems_'.SITE_ID) == 'Y') {


            foreach ($order->getShipmentCollection() as $item) {
                if (count($item->getShipmentItemCollection())) {
                    foreach($item->getShipmentItemCollection() as $shipmentItem) {
                        $basketItem = $shipmentItem->getBasketItem();
                        if ($basketItem->isBundleChild())
                            continue;



                        $orderItem = array(
                            'name'	=>	$basketItem->getField('NAME'),
                            'price'	=>	$basketItem->getPrice(),
                            'sum'	=>	$basketItem->getFinalPrice(),
                            'count' => (float) $basketItem->getQuantity(),

                        );

                        $vatInfo = $this->getProductVatInfo($basketItem);

                        if ($vatInfo) {
                            $orderItem['with_nds'] = $vatInfo['RATE'] == 18;
                        }

                        $discountPrice = 0;
                        if ($basketItem->isCustomPrice())
                        {
                            $discountPrice = $basketItem->getBasePrice() - $basketItem->getPrice();
                        }
                        else
                        {
                            if ($basketItem->getDiscountPrice() > 0)
                                $discountPrice = (float)$basketItem->getDiscountPrice();
                        }

                        $orderItems[] = $orderItem;
                    }

                    $vatInfo = $this->getDeliveryVatInfo($item);

                    $deliveryItem = array(
                        'name' => $item->getField('DELIVERY_NAME'),
                        'price' => (float)$item->getPrice(),
                        'sum' => (float)$item->getPrice(),
                        'count' => 1
                    );
                    if ($vatInfo) {
                        $deliveryItem['with_nds'] = $vatInfo['RATE'] == 18;
                    }

                    $deliveryDiscountPrice = 0;
                    if (!$item->isCustomPrice() && $item->getField('DISCOUNT_PRICE') > 0)
                    {
                        $deliveryDiscountPrice = $item->getField('DISCOUNT_PRICE');
                    }

                    $orderItems[] = $deliveryItem;
                }
            }
        }

        $paymentType = Option::get('unitpay.paymodule', 'typepay_'.SITE_ID, "");
        if($paymentType != ""){
            $url = $this->getUrl($payment, 'pay');
            $settings = $this->getParamsBusValue($payment);

            $desc = Option::get('unitpay.paymodule', 'desc_'.SITE_ID);
            $desc = (SITE_CHARSET != "UFT-8") ? iconv(SITE_CHARSET,'UTF-8',$desc) : $desc;
            $arParam = array(
                'method' => 'initPayment',
                'params' => array(
                    'paymentType' => $paymentType,
                    'account' => $settings["OrderID"],
                    'sum' => $settings["OrderSum"],
                    'projectId' => Option::get('unitpay.paymodule', 'numb_'.SITE_ID),
                    'desc' => $desc,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'secretKey' => Option::get('unitpay.paymodule', 'skey_'.SITE_ID),

                    'currency' => Option::get('unitpay.paymodule', 'curr_'.SITE_ID),
                    'locale' => Option::get('unitpay.paymodule', 'lang_'.SITE_ID),
                )
            );
            if ($userEmail) {
                $arParam['params']['customerEmail'] = $userEmail;
            }
            if ($userPhone) {
                $arParam['params']['customerPhone'] = $userPhone;
            }
            if (count($orderItems) > 0) {
                $arParam['params']['cashItems'] = base64_encode(\Bitrix\Main\Web\Json::encode($orderItems));
            }
            $arTypeWithTelefon = array('qiwi', 'sms', 'mc', 'alfaClick');
            if(in_array($paymentType, $arTypeWithTelefon)){
                $arParam['params']['phone']	=  $settings["Telefon"];
            }
            $arTypeWithOperator = array('sms');
            if(in_array($paymentType, $arTypeWithTelefon)){
                $arParam['params']['operator']	=  $settings["Operator"];
            }
            $arTypeWithWM = array('webmoney');
            if(in_array($paymentType, $arTypeWithTelefon)){
                $arParam['params']['purseType']	=  Option::get('unitpay.paymodule', 'purseType_'.SITE_ID);
            }


            $params = $this->makeRequest($url, $arParam);
            $this->setExtraParams($params);
            return $this->showTemplate($payment, 'template');
        }
        else{
            $url = $this->getUrl($payment, 'pay_all');
            $url.= Option::get('unitpay.paymodule', 'pkey_'.SITE_ID);
            $settings = $this->getParamsBusValue($payment);

            $account = $settings["OrderID"];
            $currency = Option::get('unitpay.paymodule', 'curr_'.SITE_ID);
            $desc = Option::get('unitpay.paymodule', 'desc_'.SITE_ID);
            $sum = $settings["OrderSum"];
            $SecretKey = Option::get('unitpay.paymodule', 'skey_'.SITE_ID);

            $desc_uft8 = (SITE_CHARSET != "UFT-8") ? iconv(SITE_CHARSET,'UTF-8',$desc) : $desc;
            if(strlen($SecretKey)>0)
                $signature = hash('sha256', $id.'{up}'.$currency.'{up}'.$desc_uft8.'{up}'.$sum.'{up}'.$SecretKey);

            $params = array(
                'url' => $url,
                'account' => $account,
                'sum' =>  $sum,
                'currency' => $currency,
                'locale' => Option::get('unitpay.paymodule', 'lang_'.SITE_ID),
                'desc' => $desc,
                'signature' => $signature,
            );

            if ($userEmail) {
                $params['customerEmail'] = $userEmail;
            }
            if ($userPhone) {
                $params['customerPhone'] = $userPhone;
            }
            if (count($orderItems) > 0) {
                $params['cashItems'] = base64_encode(\Bitrix\Main\Web\Json::encode($orderItems));
            }

            $this->setExtraParams($params);
            return $this->showTemplate($payment, 'template_all');
        }
        return false;
    }

    public function processRequest(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();
        $method = $request->get('method');
        if ($method  == 'check'){
            return $this->processCheckAction($payment, $request);
        }
        elseif ($method == 'pay'){
            return $this->processNoticeAction($payment, $request);
        }
        else{
            $result->addError(new Error('Incorrect parameter \'method\''));
            PaySystem\ErrorLog::add(array(
                'ACTION' => 'processRequest',
                'MESSAGE' => 'Incorrect parameter \'method\''
            ));
        }
        return $result;
    }

    private function processCheckAction(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();

        if (!$this->isCorrect($payment, $request))
        {
            $errorMessage = 'Incorrect payment \'sum\', \'currency\', \'projectId\', \'order id\'';

            $result->addError(new Error($errorMessage));
            PaySystem\ErrorLog::add(array(
                'ACTION' => 'processCheckAction',
                'MESSAGE' => $errorMessage
            ));
        }
        return $result;
    }

    private function processNoticeAction(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();
        $params = $request->get('params');

        if($this->isCorrect($payment, $request)){
            $fields = array(
                "PS_STATUS" => "Y",
                "PS_STATUS_CODE" => $params['account'],
                "PS_STATUS_DESCRIPTION" => "-",
                "PS_STATUS_MESSAGE" => "-",
                "PS_SUM" => $params['orderSum'],
                "PS_CURRENCY" => $params['orderCurrency'],
                "PS_RESPONSE_DATE" => new DateTime(),
            );
            $result->setPsData($fields);
            $result->setOperationType(PaySystem\ServiceResult::MONEY_COMING);
        }
        else{
            $errorMessage = 'Incorrect payment \'sum\', \'currency\', \'projectId\', \'order id\'';

            $result->addError(new Error($errorMessage));
            PaySystem\ErrorLog::add(array(
                'ACTION' => 'processNoticeAction',
                'MESSAGE' => $errorMessage
            ));
        }

        return $result;
    }

    private function isCorrect(Payment $payment, Request $request)
    {
        $params = $request->get('params');

        $currency = Option::get('unitpay.paymodule', 'curr_'.SITE_ID);
        $projectId  = Option::get('unitpay.paymodule', 'numb_'.SITE_ID);
        $id = $this->getBusinessValue($payment, 'OrderID');
        $paymentSum = $this->getBusinessValue($payment, 'OrderSum');
        if (
            $params['sum'] == $paymentSum
            && $params['orderCurrency'] == $currency
            && $params['account'] == $id
            && $params['projectId'] == $projectId
        )
            return true;
        return false;
    }

    private function makeRequest($url, array $arParam){
        $httpClient = new HttpClient();
        $httpClient->setHeader('Content-Type', 'application/x-www-form-urlencoded', true);
        $response  = $httpClient->post($url, http_build_query($arParam));
        $result = json_decode((string)$response, true);
        return $result;
    }

    private function getDeliveryVatInfo(Shipment $shipment){
        $deliveryVatInfo = array();

        $calcDeliveryTax = Main\Config\Option::get("sale", "COUNT_DELIVERY_TAX", "N");
        if ($calcDeliveryTax === 'Y')
        {
            /** @var ShipmentCollection $collection */
            $collection = $shipment->getCollection();

            $order = $collection->getOrder();

            $basket = $order->getBasket();

            $maxVatRate = 0;
            foreach ($basket as $basketItem)
            {
                $vatInfo = $this->getProductVatInfo($basketItem);
                if ($maxVatRate < $vatInfo['RATE'])
                {
                    $maxVatRate = $vatInfo['RATE'];
                    $deliveryVatInfo = $vatInfo;
                }
            }
        }

        return $deliveryVatInfo;
    }

    /**
     * @param BasketItem $basketItem
     * @return array|bool|false|mixed|null
     */
    private function getProductVatInfo(BasketItem $basketItem){
        static $vatInfoList = array();

        if (!isset($vatInfoList[$basketItem->getProductId()]))
        {
            if (Main\Loader::includeModule('catalog'))
            {
                $dbRes = \CCatalogProduct::GetVATInfo($basketItem->getProductId());
                $vatInfoList[$basketItem->getProductId()] = $dbRes->Fetch();
            }
        }

        return $vatInfoList[$basketItem->getProductId()];
    }
}
