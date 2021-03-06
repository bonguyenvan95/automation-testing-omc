<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 07/12/2017
 * Time: 13:30
 */

namespace Magento\Webpos\Test\Block\Order;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class OrderHistoryOrderViewContent
 * @package Magento\Webpos\Test\Block\Order
 */
class OrderHistoryOrderViewContent extends Block
{
	public function getBillingName()
	{
		return $this->_rootElement->find('label[data-bind="text: $parent.getCustomerName(\'billing\')"]')->getText();
	}

	public function getBillingAddress()
	{
		return $this->_rootElement->find('span[data-bind="text: $parent.getAddress(\'billing\')"]')->getText();
	}

	public function getBillingPhone()
	{
		return $this->_rootElement->find('span[data-bind="text: $parent.getAddressType(\'billing\').telephone"]')->getText();
	}

	public function getShippingName()
	{
		return $this->_rootElement->find('label[data-bind="text: $parent.getCustomerName(\'shipping\')"]')->getText();
	}
	public function getShippingDescription()
	{
		return $this->_rootElement->find('div > div:nth-child(2) > div:nth-child(2) > div > div.panel-body > div > label')->getText();
	}

	public function getShippingAddress()
	{
		return $this->_rootElement->find('span[data-bind="text: $parent.getAddress(\'shipping\')"]')->getText();
	}

	public function getShippingPhone()
	{
		return $this->_rootElement->find('span[data-bind="text: $parent.getAddressType(\'shipping\').telephone"]')->getText();
	}

	public function getProductRow($name)
    {
        return $this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$name.'"]/../..', Locator::SELECTOR_XPATH);
    }

	public function getProductSKU($name)
	{
		return $this->getProductRow($name)->find('span.product-sku');
	}

	public function getQtyOfProduct($name)
	{
		return $this->getProductRow($name)->find('td.order-id');
	}

	public function getOriginalPriceOfProduct($name)
	{
		return $this->getProductRow($name)->find('td[data-bind="text: $parents[1].convertAndFormatPrice(item.base_original_price)"]');
	}

    public function getPriceOfProduct($name)
    {
        return $this->getProductRow($name)->find('td[data-bind="text: $parents[1].getItemPriceFormated(item)"]');
    }

    public function getValueComment()
    {
        return $this->_rootElement->find('main > div > div:nth-child(4) > div > div > div.panel-body > div > table > tbody > tr > td:nth-child(2)')->getText();
    }

	public function getSubTotalOfProduct($name)
	{
		return $this->getProductRow($name)->find('td[data-bind="text: $parents[1].convertAndFormatPrice(item.base_row_total)"]')->getText();
	}

	public function getTaxAmountOfProduct($name)
	{
		return $this->getProductRow($name)->find('td[data-bind="text: $parents[1].convertAndFormatPrice(item.base_tax_amount)"]')->getText();
	}

	public function getDiscountAmountOfProduct($name)
	{
		return$this->_rootElement->find('//table/tbody/tr/td/h4[text()="'.$name.'"]/../../td[7]', Locator::SELECTOR_XPATH)->getText();
	}

	public function getRowTotalOfProduct($name)
	{
		return $this->getProductRow($name)->find('td[data-bind="text: $parents[1].getItemRowTotalFormated(item)"]')->getText();
	}
	public function getSubTotal()
	{
		return str_replace('$','', $this->_rootElement->find('#webpos_order_view_container > footer > div.col-sm-offset-6 > table > tbody > tr:nth-child(1) > td.a-right')->getText());
	}
	public function getTotal()
	{
        return str_replace('$','', $this->_rootElement->find('#webpos_order_view_container > footer > div.col-sm-offset-6 > table > tbody > tr:nth-child(4) > td.a-right')->getText());
	}

	public function getPaymentName()
    {
        return $this->_rootElement->find('//main/div/div[2]/div[1]/div/div[2]/div/div/label[1]', Locator::SELECTOR_XPATH);
    }

    public function getPaymentMethodName($stt)
    {
        return $this->_rootElement->find('//main/div/div[2]/div[1]/div/div[2]/div/div['.$stt.']/label[1]', Locator::SELECTOR_XPATH);
    }

	public function billingAddressBlockIsVisible()
    {
        return $this->_rootElement->find('.//*[@class="panel panel-default" and contains(.//h5, "Billing Address")]', Locator::SELECTOR_XPATH)->isVisible();
    }

    public function shippingAddressBlockIsVisible()
    {
        return $this->_rootElement->find('.//*[@class="panel panel-default" and contains(.//h5, "Shipping Address")]',Locator::SELECTOR_XPATH)->isVisible();
    }

    public function paymentMethodBlockIsVisible()
    {
        return $this->_rootElement->find('.//*[@class="panel panel-default" and contains(.//h5, "PAYMENT METHOD")]',Locator::SELECTOR_XPATH)->isVisible();
    }

    public function shippingMethodBlockIsVisible()
    {
        return $this->_rootElement->find('.//*[@class="panel panel-default" and contains(.//h5, "SHIPPING METHOD")]',Locator::SELECTOR_XPATH)->isVisible();
    }

    public function itemTableIsVisible()
    {
        return $this->_rootElement->find('.//table[@class="table"]',Locator::SELECTOR_XPATH)->isVisible();
    }

	public function getShippingAddressContent()
	{
		return $this->_rootElement->find('#webpos_order_view_container > main > div > div:nth-child(1) > div:nth-child(2) > div > div.panel-body');
	}

	public function getPaymentMethodContent()
	{
		return $this->_rootElement->find('#webpos_order_view_container > main > div > div:nth-child(2) > div:nth-child(1) > div > div.panel-body');
	}

	public function getShippingMethodContent()
	{
		return $this->_rootElement->find('#webpos_order_view_container > main > div > div:nth-child(2) > div:nth-child(2) > div > div.panel-body');
	}

	public function getPaymentMethod($label)
	{
		return $this->_rootElement->find('//*[@id="webpos_order_view_container"]/main/div/div[2]/div[1]/div/div[2]/div/div/label[text()="'.$label.'"]/..', Locator::SELECTOR_XPATH);
	}

	public function getPaymentMethodAmount($label)
	{
		return $this->getPaymentMethod($label)->find('label[data-bind="text: $parents[1].getWebposPaymentAmount($data)"]');
	}

	public function getShippedQty($productName)
    {
        $qtyColumn = $this->getQtyOfProduct($productName)->getText();
        preg_match('/Shipped: (\d+)/', $qtyColumn, $match);
        return $match[1];
    }

    public function getNameProductOrderTo($i)
    {
        return $this->_rootElement->find('//div/div[3]/div/div/div/table/tbody/tr['.$i.']', Locator::SELECTOR_XPATH)->find('.product-name')->getText();
    }

    public function getPriceProductByOrderTo($i)
    {
        return floatval(str_replace('$','',$this->_rootElement->find('//div/div[3]/div/div/div/table/tbody/tr['.$i.']/td[3]', Locator::SELECTOR_XPATH)->getText()));
    }
}