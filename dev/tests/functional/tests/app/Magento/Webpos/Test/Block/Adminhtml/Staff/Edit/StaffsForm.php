<?php
/**
 * @Author: Thomas Mr 0
 * @Created At:   2017-09-07 14:35:45
 * @Email:  thomas@trueplus.vn
 * @Last Modified by:   thomas
 * @Last Modified time: 2017-09-07 14:41:26
 * @Links : https://www.facebook.com/Onjin.Matsui.VTC.NQC
 */
namespace Magento\Webpos\Test\Block\Adminhtml\Staff\Edit;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;

class StaffsForm extends Form
{
    public function getMessageRequired($id)
    {
        return $this->_rootElement->find('#'.$id)->getText();
    }
    public function isMessageRequiredDisplay($id)
    {
        return $this->_rootElement->find('#'.$id);
    }

    public function getPassword()
    {
        return $this->_rootElement->find('#page_password')->getText();
    }

    public function setPassword($pass)
    {
        $this->_rootElement->find('#page_password')->setValue($pass);
    }

    public function getConfimPassword()
    {
        return $this->_rootElement->find('#page_password_confirmation')->getText();
    }

    public function setConfimPassword($passConfim)
    {
        $this->_rootElement->find('#page_password_confirmation')->setValue($passConfim);
    }

    public function getDisplayName()
    {
        return $this->_rootElement->find('#page_display_name')->getValue();
    }

    public function setDisplayName($displayName)
    {
        $this->_rootElement->find('#page_display_name')->setValue($displayName);
    }

    public function getUserName()
    {
        return $this->_rootElement->find('#page_username')->getValue();
    }

    public function setUserName($name)
    {
        $this->_rootElement->find('#page_username')->setValue($name);
    }

    public function getEmailAddress()
    {
        return $this->_rootElement->find('#page_email')->getValue();
    }

    public function setEmailAddress($email)
    {
        $this->_rootElement->find('#page_email')->setValue($email);
    }

    public function getPinCode()
    {
        return $this->_rootElement->find('#page_pin')->getValue();
    }

    public function setPinCode($pin)
    {
        $this->_rootElement->find('#page_pin')->setValue($pin);
    }

    public function getCustomerGroup()
    {
        $value = $this->_rootElement->find('#page_customer_group')->getValue();
        if($value == null)
            return '';
        return $this->_rootElement->find('#page_customer_group')->find('[value="'.$value.'"]')->getText();
    }

    public function getLocation()
    {
        $value = $this->_rootElement->find('#page_location_id')->getValue();
        if($value == null)
            return '';
        return $this->_rootElement->find('#page_location_id')->find('[value="'.$value.'"]')->getText();
    }

    public function setLocation($nameLocation)
    {
        $this->_rootElement->find('#page_location_id', Locator::SELECTOR_CSS, 'multiselect')->setValue($nameLocation);
    }

    public function getRow()
    {
        $value = $this->_rootElement->find('#page_role_id')->getValue();
        if($value == null)
            return '';
        return $this->_rootElement->find('#page_role_id')->find('[value="'.$value.'"]')->getText();
    }

    public function getStatus()
    {
        $value = $this->_rootElement->find('#page_status')->getValue();
        if($value == null)
            return '';
        return $this->_rootElement->find('#page_status')->find('[value="'.$value.'"]')->getText();
    }

    public function getPos()
    {
        $value = $this->_rootElement->find('#page_pos_ids')->getValue();
        if($value == null)
            return '';
        return $this->_rootElement->find('#page_pos_ids')->find('[value="'.$value.'"]')->getText();
    }

    public function setPos($namePos)
    {
        $this->_rootElement->find('#page_pos_ids', Locator::SELECTOR_CSS, 'multiselect')->setValue($namePos);
    }

    public function getTextBoxMessagePassConfim()
    {
        return $this->_rootElement->find('#page_password_confirmation-error');
    }

    public function getTextBoxMessagePassword()
    {
        return $this->_rootElement->find('#page_password-error');
    }

    public function getTextBoxEmailValid()
    {
        return $this->_rootElement->find('#page_email-error');
    }

    public function getFieldById($id)
    {
        return $this->_rootElement->find('#'.$id);
    }

}
