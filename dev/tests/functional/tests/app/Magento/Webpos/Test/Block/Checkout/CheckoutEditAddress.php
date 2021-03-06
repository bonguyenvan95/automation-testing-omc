<?php
/**
 * Created by PhpStorm.
 * User: vinh
 * Date: 08/12/2017
 * Time: 14:17
 */

namespace Magento\Webpos\Test\Block\Checkout;


use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

class CheckoutEditAddress extends Block
{
	public function getCancelButton()
	{
		return $this->_rootElement->find('.close');
	}

	public function getSaveButton()
	{
		return $this->_rootElement->find('.btn-save');
	}

	public function getFirstNameInput()
	{
		return $this->_rootElement->find('input[name="first-name"]');
	}

	public function getLastNameInput()
	{
		return $this->_rootElement->find('input[name="last-name"]');
	}

	public function getCompanyInput()
	{
		return $this->_rootElement->find('input[name="company"]');
	}

	public function getPhoneInput()
	{
		return $this->_rootElement->find('input[name="phone"]');
	}

	public function getStreet1Input()
	{
		return $this->_rootElement->find('input[name="street1"]');
	}

	public function getStreet2Input()
	{
		return $this->_rootElement->find('input[name="Street2"]');
	}

	public function getCityInput()
	{
		return $this->_rootElement->find('input[name="city"]');
	}

	public function getZipCodeInput()
	{
		return $this->_rootElement->find('input[name="zipcode"]');
	}

	public function getVATInput()
	{
		return $this->_rootElement->find('input[name="vat"]');
	}

	public function clickCountrySelect()
	{
		$this->_rootElement->find('select[name="country_id"]')->click();
		sleep(1);
	}

	public function getCountryItem($name)
	{
		return $this->_rootElement->find('//select[@name="country_id"]/option[text()="' . $name . '"]', Locator::SELECTOR_XPATH);
	}

	public function clickRegionSelect()
	{
		$this->_rootElement->find('select[name="region_id"]')->click();
		sleep(1);
	}

	public function getRegionItem($name)
	{
		return $this->_rootElement->find('//select[@name="region_id"]/option[text()="' . $name . '"]', Locator::SELECTOR_XPATH);
	}

	public function getRegionId()
    {
        return $this->_rootElement->find('[name="region_id"]', Locator::SELECTOR_CSS, 'select');
    }

    public function setFiledAdress($data)
    {
        foreach ($data as $item => $value){
            switch ($item)
            {
                case 'firstname' :
                    $this->_rootElement->find('input[name="first-name"]')->setValue($value);
                    break;

                case 'lastname' :
                    $this->_rootElement->find('input[name="last-name"]')->setValue($value);
                    break;

                case 'company' :
                    $this->_rootElement->find('input[name="company"]')->setValue($value);
                    break;

                case 'telephone' :
                    $this->_rootElement->find('input[name="phone"]')->setValue($value);
                    break;

                case 'street' :
                    $this->_rootElement->find('input[name="street1"]')->setValue($value);
                    break;

                case 'city' :
                    $this->_rootElement->find('input[name="city"]')->setValue($value);
                    break;

                case 'postcode' :
                    $this->_rootElement->find('input[name="zipcode"]')->setValue($value);
                    break;

                case 'country_id' :
                    $this->_rootElement->find('#add_address_country_id', Locator::SELECTOR_CSS, 'select')->setValue($value);
                    break;

                case 'region' :
                    $this->_rootElement->find('#add_address_region_id', Locator::SELECTOR_CSS, 'select')->setValue($value);
                    break;

            }
        }

    }

    public function waingPageLoading()
    {
        $this->waitForElementNotVisible('#customer-overlay');
    }

    public function waitForPopupVisible(){
	    $this->waitForElementVisible('#form-customer-add-address-checkout');
    }
}