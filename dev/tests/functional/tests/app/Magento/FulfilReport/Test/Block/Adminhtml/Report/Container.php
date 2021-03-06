<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 27/11/2017
 * Time: 14:19
 */
namespace Magento\FulfilReport\Test\Block\Adminhtml\Report;

use Magento\Mtf\Block\Block;
/**
 * Class Container
 * @package Magento\FulfilSuccess\Test\Block\Adminhtml\Report
 */
class Container extends Block
{
    /**
     * @return \Magento\Mtf\Client\ElementInterface
     */
    public function getTitleStaffReport()
    {
        return $this->_rootElement->find('#container > div > div > div:nth-child(1) > div.panel-heading');
    }
    public function getTitleWarehouseReport()
    {
        return $this->_rootElement->find('#container > div > div > div:nth-child(2) > div.panel-heading');
    }
    public function getIconBackendFulfilStaff()
    {
        return $this->_rootElement->find('.icon-icon-Back-end-fulfilstaff');
    }
    public function getIconBackendFulfilStaffDaily()
    {
        return $this->_rootElement->find('.icon-icon-Back-end-fulfilstaffdaily');
    }
    public function getIconFulfilWarehouse()
    {
        return $this->_rootElement->find('.icon-icon-Back-end-fulfilwarehouse');
    }
    public function getIconFulfilWarehouseDaily()
    {
        return $this->_rootElement->find('.icon-icon-Back-end-fulfilwarehousedaily');
    }
    public function getTitleOrderSection()
    {
        return $this->_rootElement->find('#ordersuccess_order-head');
    }
    public function getFirstFieldOrder()
    {
        return $this->_rootElement->find('#ordersuccess_order_verify');
    }
    public function getTitleFulfillmentSection()
    {
        return $this->_rootElement->find('#fulfilsuccess_scanning-head');
    }
    public function getFirstFieldFulfillment()
    {
        return $this->_rootElement->find('#fulfilsuccess_scanning_barcode');
    }
    public function getTitleDropshipSection()
    {
        return $this->_rootElement->find('#dropshipsuccess_general-head');
    }
    public function getFirstFieldDropship()
    {
        return $this->_rootElement->find('#dropshipsuccess_general_submit_request_to_supplier');
    }
}