<?php
/**
 * Created by: thomas
 * Date: 12/10/2017
 * Time: 11:12
 * Email:  thomas@trueplus.vn
 * Links : https://www.facebook.com/Onjin.Matsui.VTC.NQC
 */

namespace Magento\Webpos\Test\TestCase\CustomerList\Checkout;


use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Fixture\Staff;
use Magento\Webpos\Test\Page\WebposIndex;

/**
 * Class CancelOrderOfCustomerCheckoutEntityTest
 * @package Magento\Webpos\Test\TestCase\CustomerList
 */
class CancelOrderOfCustomerCheckoutEntityTest extends Injectable
{
    /**
     * Webpos Index page.
     *
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * Inject Webpos Login pages.
     *
     * @param WebposIndex $webposIndex
     * @return void
     */
    public function __inject(
        WebposIndex $webposIndex
    )
    {
        $this->webposIndex = $webposIndex;
    }

    /**
     * Login Webpos group test.
     *
     * @param Staff $staff
     * @return void
     */
    public function test(Staff $staff, $emailInitial, $products, $comment)
    {
        $this->webposIndex->open();
        $this->webposIndex->getLoginForm()->fill($staff);
        $this->webposIndex->getLoginForm()->clickLoginButton();
        sleep(3);
        while ($this->webposIndex->getFirstScreen()->isVisible()) {
        }
        sleep(2);

        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        $this->webposIndex->getCmenu()->customerList();
        sleep(1);
        $this->webposIndex->getCustomerListContainer()->searchCustomer()->setValue($emailInitial);
        sleep(2);
        $this->webposIndex->getCustomerListContainer()->useToCheckout()->click();
        sleep(2);

        $this->webposIndex->getCheckoutPage()->clickFirstProduct();
        foreach ($products as $product) {
            $this->webposIndex->getCheckoutPage()->search($product);
        }
        sleep(2);
        $this->webposIndex->getCheckoutPage()->clickCheckoutButton();
        sleep(1);
        $this->webposIndex->getCheckoutPage()->selectPayment();
        sleep(1);
        $this->webposIndex->getCheckoutPage()->setPaidAmount('0');
        sleep(1);
        $this->webposIndex->getCheckoutPage()->clickPlaceOrder();
        //define array variable for assertion
        $result = [];
        $result['notify-order-text'] = $this->webposIndex->getToaster()->getWarningMessage();
        sleep(1);
        $email = $this->webposIndex->getCheckoutPage()->getCustomerEmail();
        sleep(2);
        $this->webposIndex->getCheckoutPage()->sendEmail($email);
        sleep(2);
        $result['send-email-success'] = $this->webposIndex->getToaster()->getWarningMessage();
        sleep(2);
        $result['order-id'] = $this->webposIndex->getCheckoutPage()->getOrderId();
        sleep(1);

        $this->webposIndex->getCheckoutPage()->clickNewOrderButton();

        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        sleep(1);
        $this->webposIndex->getCMenu()->ordersHistory();
        sleep(1);
        $value = str_replace('#', '', $result['order-id']);
        $this->webposIndex->getOrdersHistory()->search($value);
        sleep(1);
        $this->webposIndex->getOrdersHistory()->getMoreInfoButton()->click();
        sleep(1);
        $this->webposIndex->getOrdersHistory()->getAction('Cancel')->click();
        sleep(1);
        $this->webposIndex->getOrdersHistory()->getCommentAreaBox()->setValue($comment);
        sleep(1);
        $this->webposIndex->getOrdersHistory()->getSaveButton()->click();
        sleep(1);
        $this->webposIndex->getModal()->getOkButton()->click();
        sleep(2);

        $result['cancel-order'] = $this->webposIndex->getToaster()->getWarningMessage();
        return ['result' => $result];

    }
}
