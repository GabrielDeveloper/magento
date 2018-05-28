<?php

class Mundipagg_Paymentmodule_Helper_Paymentformat extends Mage_Core_Helper_Abstract
{

    public function getFormatedData($data, $paymentMethod)
    {
        $result = array_filter(
            $data,
            function ($k) use ($paymentMethod) {
                return preg_match('/^' . $paymentMethod . '/', $k);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $this->formatData($result, $paymentMethod);
    }

    protected function formatData($data, $paymentMethod)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $keys = explode($paymentMethod .'_', $key)[1];
            $keys = explode('_', $keys);

            $result[$keys[0]][$keys[1]][$keys[2]] = $value;
        }

        if (!$this->validate($result)) {
            return [];
        }

        return $result;
    }

    /**
     * Prevent not allowed input data
     * @return $this|Mage_Payment_Model_Abstract
     * @throws Mage_Core_Exception
     * @todo Improve this method
     */
    public function validate($paymentData = null)
    {
        if (!$paymentData) {
            return $this;
        }

        $validation = true;
        $amount = 0;

        foreach ($paymentData as $key => $payment) {
            $validation = Mage::getModel('paymentmodule/' . $key)
                ->validatePaymentData($paymentData[$key]);

            $amount += $this->getAmountFromPaymentData($paymentData[$key]);
        }

        if (!$this->validateOrderAmount($amount)) {
            $helperLog = Mage::helper('paymentmodule/log');
            $helperLog->info("Amount different of Order");
            $helperLog->info("Order amount: " . $this->getGrandTotalPerOrder());
            $helperLog->info("Amount Selected: " . $amount);
            $validation = false;
        }

        if (!$validation) {
            $errorMsg = Mage::helper('paymentmodule')
                ->__('Invalid payment data');
            Mage::throwException($errorMsg);

            return false;
        }

        return true;
    }

    protected function getAmountFromPaymentData($paymentData)
    {
        $amount = 0;
        foreach ($paymentData as $payment) {
            $amount += (float) $payment['value'];
        }

        return $amount;
    }

    protected function validateOrderAmount($amount)
    {
        return $amount == $this->getGrandTotalPerOrder();
    }

    public function getGrandTotalPerOrder()
    {
        return (int) $this->getQuote()
                        ->getGrandTotal();
    }

    public function getQuote()
    {
        return $this->getCheckoutSession()                                                                                                     
            ->getQuote();
    }

    public function getCheckoutSession()
    {
        return Mage::getModel('checkout/session');
    }
}