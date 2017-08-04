<?php

namespace craft\commerce\base;

use Craft;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\payments\CreditCardPaymentForm;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\AbstractRequest;

/**
 * This is an abstract class to be used by credit card gateways
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) 2017, Pixel & Tonic, Inc.
 * @license   https://craftcommerce.com/license Craft Commerce License Agreement
 * @see       https://craftcommerce.com
 * @package   craft.commerce
 * @since     2.0
 */
abstract class CreditCardGateway extends Gateway
{

    /**
     * @inheritdoc
     */
    public function requiresCreditCard()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormModel()
    {
        return new CreditCardPaymentForm();
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormHtml(array $params)
    {
        $defaults = [
            'gateway' => $this,
            'paymentForm' => $this->getPaymentFormModel()
        ];

        $params = array_merge($defaults, $params);

        return Craft::$app->getView()->renderTemplate('commerce/_components/gateways/common/creditCardPaymentForm', $params);
    }

    /**
     * @inheritdoc
     */
    public function populateCard(CreditCard $card, CreditCardPaymentForm $paymentForm)
    {
        $card->setFirstName($paymentForm->firstName);
        $card->setLastName($paymentForm->lastName);
        $card->setNumber($paymentForm->number);
        $card->setExpiryMonth($paymentForm->month);
        $card->setExpiryYear($paymentForm->year);
        $card->setCvv($paymentForm->cvv);
    }

    /**
     * @inheritdoc
     */
    public function populateRequest(AbstractRequest $request, BasePaymentForm $paymentForm)
    {
        if ($paymentForm->hasProperty('token')) {
            $request->setToken($paymentForm->token);
        }
    }
}