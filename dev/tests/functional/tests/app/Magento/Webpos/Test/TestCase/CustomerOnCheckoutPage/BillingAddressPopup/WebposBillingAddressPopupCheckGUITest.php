<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 2/23/2018
 * Time: 1:39 PM
 */

namespace Magento\Webpos\Test\TestCase\CustomerOnCheckoutPage\BillingAddressPopup;

use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;

/**
 * Class WebposBillingAddressPopupCheckGUITest
 * @package Magento\Webpos\Test\TestCase\CustomerOnCheckoutPage\BillingAddressPopup
 */
class WebposBillingAddressPopupCheckGUITest extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * @param WebposIndex $webposIndex
     */
    public function __inject(
        WebposIndex $webposIndex
    )
    {
        $this->webposIndex = $webposIndex;
    }

    /**
     * @param null $action
     */
    public function test(
        $action = null
    )
    {
        // Login webpos
        $staff = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\LoginWebposStep'
        )->run();

        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getMsWebpos()->waitCartLoader();

        $this->webposIndex->getCheckoutCartHeader()->getIconAddCustomer()->click();
        $this->webposIndex->getCheckoutChangeCustomer()->waitForCustomerList();

        $this->webposIndex->getCheckoutChangeCustomer()->getAddNewCustomerButton()->click();
        sleep(1);
        $this->webposIndex->getCheckoutAddCustomer()->getAddBillingAddressIcon()->click();
        sleep(1);

        if (strcmp($action, 'cancel') == 0) {
            $this->webposIndex->getCheckoutAddBillingAddress()->getCancelButton()->click();
        } elseif (strcmp($action, 'save') == 0) {
            $this->webposIndex->getCheckoutAddBillingAddress()->getSaveButton()->click();
        }
    }
}