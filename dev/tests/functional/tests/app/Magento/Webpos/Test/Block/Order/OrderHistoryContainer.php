<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 06/12/2017
 * Time: 08:47
 */

namespace Magento\Webpos\Test\Block\Order;

use Magento\Mtf\Block\Block;
/**
 * Class OrderHistoryContainer
 * @package Magento\Webpos\Test\Block\Order
 */
class OrderHistoryContainer extends Block
{
    /**
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getSearchOrderInput()
    {
        return $this->_rootElement->find('#search-header-order');
    }

    /**
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getOrderNote()
	{
		return $this->_rootElement->find('div.order-comment-list > table > tbody > tr > td:nth-child(2)');
	}

    /**
     * @return bool|null
     */
    public function waitOrderHistoryInvoiceIsVisible()
    {
        return $this->waitForElementVisible('#invoice-popup');
    }

    /**
     * @return bool|null
     */
    public function waitOrderHistoryInvoiceIsNotVisible()
    {
        return $this->waitForElementNotVisible('#invoice-popup');
    }

	public function getActionsBox()
	{
		return $this->_rootElement->find('#form-add-note-order');
	}

	/**
	 * @return bool|null
	 */
	public function waitForRefundPopupIsVisible()
	{
		return $this->waitForElementVisible('#refund-popup');
	}
	public function waitForCreditMemoPopupFormIsVisible()
	{
		return $this->waitForElementVisible('#creditmemo-popup-form');
	}

	public function waitForShipmentPopupIsVisible()
	{
		return $this->waitForElementVisible('#shipment-popup');
	}

	public function getOrdersList()
    {
        return $this->_rootElement->find('[id="webpos_order_list"]');
    }

    public function getOrdersDetail()
    {
        return $this->_rootElement->find('[id="webpos_order_view_container"]');
    }

	public function waitForCancelPopupIsVisible()
	{
		$this->waitForElementVisible('#add-cancel-comment-order');
	}
}