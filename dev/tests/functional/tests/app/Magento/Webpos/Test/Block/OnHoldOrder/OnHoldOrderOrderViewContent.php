<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 1/5/2018
 * Time: 1:41 PM
 */

namespace Magento\Webpos\Test\Block\OnHoldOrder;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class OnHoldOrderOrderViewContent
 * @package Magento\Webpos\Test\Block\OnHoldOrder
 */
class OnHoldOrderOrderViewContent extends Block
{
    /**
     * @return array|string
     */
    public function getBillingName()
    {
        return $this->_rootElement->find('label[data-bind="text: $parent.getCustomerName(\'billing\')"]')->getText();
    }

    /**
     * @return array|string
     */
    public function getBillingAddress()
    {
        return $this->_rootElement->find('span[data-bind="text: $parent.getAddress(\'billing\')"]')->getText();
    }

    /**
     * @return array|string
     */
    public function getBillingPhone()
    {
        return $this->_rootElement->find('span[data-bind="text: $parent.getAddressType(\'billing\').telephone"]')->getText();
    }

    /**
     * @return array|string
     */
    public function getShippingName()
    {
        return $this->_rootElement->find('label[data-bind="text: $parent.getCustomerName(\'shipping\')"]')->getText();
    }

    /**
     * @return array|string
     */
    public function getShippingAddress()
    {
        return $this->_rootElement->find('span[data-bind="text: $parent.getAddress(\'shipping\')"]')->getText();
    }

    /**
     * @return array|string
     */
    public function getShippingPhone()
    {
        return $this->_rootElement->find('span[data-bind="text: $parent.getAddressType(\'shipping\').telephone"]')->getText();
    }

    /**
     * @param $productName
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getProductRow($productName)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$productName.'"]/../..', Locator::SELECTOR_XPATH);
    }

    /**
     * @param $productName
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getPriceOfProduct($productName)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$productName.'"]/../../td[3]', Locator::SELECTOR_XPATH);

    }

    /**
     * @param $productName
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getSubtotalOfProduct($productName)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$productName.'"]/../../td[5]', Locator::SELECTOR_XPATH);
    }

    /**
     * @param $productName
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getTaxAmountOfProduct($productName)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$productName.'"]/../../td[6]', Locator::SELECTOR_XPATH);

    }

    /**
     * @param $productName
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getDiscountAmountOfProduct($productName)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$productName.'"]/../../td[7]', Locator::SELECTOR_XPATH);
    }

    /**
     * @param $productName
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getRowTotalOfProduct($productName)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$productName.'"]/../../td[8]', Locator::SELECTOR_XPATH);
    }
}