<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 2/1/2018
 * Time: 2:06 PM
 */

namespace Magento\Webpos\Test\TestCase\OrdersHistory\Invoice;

use Magento\Webpos\Test\Page\WebposIndex;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Constraint\OrderHistory\CheckGUI\AssertWebposOrdersHistoryInvoice;

/**
 * Class WebposOrdersHistoryInvoiceOH111Test
 * @package Magento\Webpos\Test\TestCase\OrdersHistory\Invoice
 */
class WebposOrdersHistoryInvoiceOH111Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * @var AssertWebposOrdersHistoryInvoice
     */
    protected $assertWebposOrdersHistoryInvoice;

    /**
     * @param WebposIndex $webposIndex
     * @param AssertWebposOrdersHistoryInvoice $assertWebposOrdersHistoryInvoice
     */
    public function __inject(
        WebposIndex $webposIndex,
        AssertWebposOrdersHistoryInvoice $assertWebposOrdersHistoryInvoice
    )
    {
        $this->webposIndex = $webposIndex;
        $this->assertWebposOrdersHistoryInvoice = $assertWebposOrdersHistoryInvoice;
    }

    /**
     * @param $products
     * @return array
     */
    public function test(
        $products
    )
    {
        // Create products
        $products = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\CreateNewProductsStep',
            ['products' => $products]
        )->run();

        // Login webpos
        $staff = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\LoginWebposStep'
        )->run();

        // Add product to cart
        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\AddProductToCartStep',
            ['products' => $products]
        )->run();

        // Place Order
        $this->webposIndex->getCheckoutCartFooter()->getButtonCheckout()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        $this->webposIndex->getCheckoutPaymentMethod()->getCashOnDeliveryMethod()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();

        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\PlaceOrderSetShipAndCreateInvoiceSwitchStep',
            [
                'createInvoice' => false,
                'shipped' => false
            ]
        )->run();
        $this->webposIndex->getCheckoutPlaceOrder()->getButtonPlaceOrder()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        $this->webposIndex->getCheckoutSuccess()->getNewOrderButton()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();

        // Order history
        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        $this->webposIndex->getCMenu()->ordersHistory();
        sleep(2);
        $this->webposIndex->getOrderHistoryOrderList()->waitLoader();
        $this->webposIndex->getOrderHistoryOrderList()->getFirstOrder()->click();

        // Take payment
        $this->webposIndex->getOrderHistoryOrderViewHeader()->getTakePaymentButton()->click();
        $this->webposIndex->getOrderHistoryPayment()->getPaymentMethod('Web POS - Cash In')->click();
        $paymentPrice = (float) substr( $this->webposIndex->getOrderHistoryPayment()->getPaymentPriceInput()->getValue(), 1);
        $this->webposIndex->getOrderHistoryPayment()->getPaymentPriceInput()->setValue($paymentPrice / 2);
        $this->webposIndex->getOrderHistoryPayment()->getSubmitButton()->click();
        $this->webposIndex->getMsWebpos()->waitForModalPopup();
        $this->webposIndex->getModal()->getOkButton()->click();
        sleep(1);
        $totalPaid = (float) substr( $this->webposIndex->getOrderHistoryOrderViewFooter()->getTotalPaid(), 1);

        // Click Button Invoice
        $this->webposIndex->getOrderHistoryOrderViewFooter()->getInvoiceButton()->click();
        $this->webposIndex->getOrderHistoryContainer()->waitOrderHistoryInvoiceIsVisible();

        return [
            'products' => $products,
            'totalPaid' => $totalPaid
        ];
    }
}