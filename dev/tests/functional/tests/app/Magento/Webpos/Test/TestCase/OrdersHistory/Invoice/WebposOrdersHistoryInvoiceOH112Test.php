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
use Magento\Webpos\Test\Constraint\OrderHistory\Invoice\AssertInvoiceSuccess;

/**
 * Class WebposOrdersHistoryInvoiceOH112Test
 * @package Magento\Webpos\Test\TestCase\OrdersHistory\Invoice
 */
class WebposOrdersHistoryInvoiceOH112Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * @var AssertInvoiceSuccess
     */
    protected $assertInvoiceSuccess;

    /**
     * @param WebposIndex $webposIndex
     * @param AssertInvoiceSuccess $assertInvoiceSuccess
     */
    public function __inject(
        WebposIndex $webposIndex,
        AssertInvoiceSuccess $assertInvoiceSuccess
    )
    {
        $this->webposIndex = $webposIndex;
        $this->assertInvoiceSuccess = $assertInvoiceSuccess;
    }

    /**
     * @param $products
     * @return array
     */
    public function test(
        $products,
        $dataConfig
    ) {
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
        //Config payment method
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => $dataConfig]
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
        //Refresh Webpos
        $this->webposIndex->open();
        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        // Order history
        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        $this->webposIndex->getCMenu()->ordersHistory();
        $this->webposIndex->getMsWebpos()->waitOrdersHistoryVisible();
        $this->webposIndex->getOrderHistoryOrderList()->waitLoader();
        //select order
        $this->webposIndex->getOrderHistoryOrderList()->getFirstOrder()->click();
        // Take payment
        $this->webposIndex->getOrderHistoryOrderViewHeader()->getTakePaymentButton()->click();
        if ($this->webposIndex->getOrderHistoryPayment()->getPaymentMethod('Web POS - Cash In')->isVisible()) {
            $this->webposIndex->getOrderHistoryPayment()->getPaymentMethod('Web POS - Cash In')->click();
            $paymentPrice = (float)substr($this->webposIndex->getOrderHistoryPayment()->getPaymentPriceInput()->getValue(), 1);
            sleep(0.5);
            $this->webposIndex->getOrderHistoryPayment()->getPaymentPriceInput()->setValue($paymentPrice / 2);
            sleep(0.5);
            $this->webposIndex->getOrderHistoryPayment()->getSubmitButton()->click();
            sleep(0.5);
            $this->webposIndex->getMsWebpos()->waitForModalPopup();
            sleep(0.5);
            $this->webposIndex->getModal()->getOkButton()->click();
            sleep(1);

            // Click Button Invoice
            $this->webposIndex->getOrderHistoryOrderViewFooter()->getInvoiceButton()->click();
            $this->webposIndex->getOrderHistoryContainer()->waitOrderHistoryInvoiceIsVisible();
            sleep(0.5);
            $this->webposIndex->getOrderHistoryInvoice()->getSubmitButton()->click();
            $this->webposIndex->getMsWebpos()->waitForModalPopup();
            sleep(0.5);
            $this->webposIndex->getModal()->getOkButton()->click();

            // Assert Invoice successful.
            sleep(1);
            $this->assertInvoiceSuccess->processAssert($this->webposIndex);

            $this->webposIndex->getOrderHistoryOrderViewHeader()->waitForProcessingStatusVisisble();
        }
        return [
            'products' => $products
        ];
    }
}