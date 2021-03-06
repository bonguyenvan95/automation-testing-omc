<?php

/**
 * @Author: thomas
 * @Created At:   2017-09-15 12:41:49
 * @Email:  thomas@trueplus.vn
 * @Last Modified by:   thomas
 * @Last Modified time: 2017-09-15 12:42:21
 * @Links : https://www.facebook.com/Onjin.Matsui.VTC.NQC
 */

namespace Magento\Webpos\Test\Block;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class MsWebpos
 * @package Magento\Webpos\Test\Block
 */
class MsWebpos extends Block
{
	public function clickCMenuButton()
	{
		$this->_rootElement->find('#c-button--push-left')->click();
	}

	public function getCMenuButton()
	{
		return $this->_rootElement->find('#c-button--push-left');
	}

	public function getLoader()
	{
		return $this->_rootElement->find('#checkout-loader');
	}

	public function getHide()
    {
        return $this->_rootElement->find('#c-mask');
    }

	public function waitCartLoader()
	{
		$this->waitForElementNotVisible('#webpos_cart > div.indicator');
	}
//	public function isCartLoaderVisible(){
//       return $this->_rootElement.find('#webpos_cart > div.indicator')->isVisible();
//    }

	public function waitCartLoaderVisibleToNotVisible()
	{
		$this->waitForElementVisible('#webpos_cart > div.indicator');
		$this->waitForElementNotVisible('#webpos_cart > div.indicator');
	}

	public function waitCheckoutLoader()
	{
		$this->waitForElementNotVisible('#webpos_checkout > div.indicator');
	}

	public function clickOutsidePopup()
	{
		$this->_rootElement->click();
	}

	public function waitForSyncDataAfterLogin()
	{
		$this->waitForElementVisible('.first-screen');
		$this->waitForElementNotVisible('.first-screen');
	}

	public function waitForSyncDataVisible()
	{
		$this->waitForElementVisible('[class="first-screen"]');
	}

	public function waitForModalPopup()
	{
		$this->waitForElementVisible('.modals-wrapper');
	}

	public function waitOrdersHistoryVisible()
    {
        $this->waitForElementVisible('[id="orders_history_container"]');
    }

    public function cmenuButtonIsVisible()
    {
        return $this->_rootElement->find('#c-button--push-left')->isVisible();
    }
}
