<?php
/**
 * Created by PhpStorm.
 * User: vong
 * Date: 22/01/2018
 * Time: 08:18
 */

namespace Magento\Webpos\Test\TestCase\Tax\CheckoutWithGroupProduct;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Constraint\Checkout\CheckGUI\AssertWebposCheckoutPagePlaceOrderPageSuccessVisible;
use Magento\Webpos\Test\Constraint\OrderHistory\AssertOrderStatus;
use Magento\Webpos\Test\Constraint\OrderHistory\Invoice\AssertInvoiceSuccess;
use Magento\Webpos\Test\Constraint\OrderHistory\Payment\AssertPaymentSuccess;
use Magento\Webpos\Test\Constraint\OrderHistory\Refund\AssertRefundSuccess;
use Magento\Webpos\Test\Constraint\OrderHistory\Shipment\AssertShipmentSuccess;
use Magento\Webpos\Test\Page\WebposIndex;

/**
 * Class WebposTaxTAX119Test
 * @package Magento\Webpos\Test\TestCase\Tax\CheckoutWithGroupProduct
 */
class WebposTaxTAX119Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * @var AssertPaymentSuccess
     */
    protected $assertPaymentSuccess;

    /**
     * @var AssertInvoiceSuccess
     */
    protected $assertInvoiceSuccess;

    /**
     * @var AssertShipmentSuccess
     */
    protected $assertShipmentSuccess;

    /**
     * @var AssertRefundSuccess
     */
    protected $assertRefundSuccess;

    /**
     * @var AssertOrderStatus
     */
    protected $assertOrderStatus;

    /**
     * @var AssertWebposCheckoutPagePlaceOrderPageSuccessVisible
     */
    protected $assertWebposCheckoutPagePlaceOrderPageSuccessVisible;

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'johndoe_MI_unique_first_name']);
        $customer->persist();

        $taxRate = $fixtureFactory->createByCode('taxRate', ['dataset' => 'US-MI-Rate_1']);
        $this->objectManager->create('Magento\Tax\Test\Handler\TaxRate\Curl')->persist($taxRate);

        // Config: use system value for all field in Tax Config
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'default_tax_configuration_use_system_value']
        )->run();

        return [
            'customer' => $customer,
            'taxRate' => $taxRate->getRate()
        ];
    }

    public function __inject(
        WebposIndex $webposIndex,
        FixtureFactory $fixtureFactory,
        AssertPaymentSuccess $assertPaymentSuccess,
        AssertInvoiceSuccess $assertInvoiceSuccess,
        AssertShipmentSuccess $assertShipmentSuccess,
        AssertRefundSuccess $assertRefundSuccess,
        AssertOrderStatus $assertOrderStatus,
        AssertWebposCheckoutPagePlaceOrderPageSuccessVisible $assertWebposCheckoutPagePlaceOrderPageSuccessVisible
    )
    {
        $this->webposIndex = $webposIndex;
        $this->fixtureFactory = $fixtureFactory;
        $this->assertPaymentSuccess = $assertPaymentSuccess;
        $this->assertInvoiceSuccess = $assertInvoiceSuccess;
        $this->assertShipmentSuccess = $assertShipmentSuccess;
        $this->assertRefundSuccess = $assertRefundSuccess;
        $this->assertOrderStatus = $assertOrderStatus;
        $this->assertWebposCheckoutPagePlaceOrderPageSuccessVisible = $assertWebposCheckoutPagePlaceOrderPageSuccessVisible;
    }

    public function test(
        Customer $customer,
        $taxRate,
        $products,
        $createInvoice = true,
        $shipped = false,
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
        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();

        foreach ($products as $item) {
            $this->webposIndex->getCheckoutProductList()->search($item['product']->getSku());
            $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
            $this->webposIndex->getCheckoutContainer()->waitForProductDetailPopup();
            sleep(5);
            $this->webposIndex->getCheckoutProductDetail()->fillGroupedProductQty($item['product']);
            $this->webposIndex->getCheckoutProductDetail()->getButtonAddToCart()->click();
            $this->webposIndex->getMsWebpos()->waitCartLoader();
        }

        // change customer
        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\ChangeCustomerOnCartStep',
            ['customer' => $customer]
        )->run();
        //Config payment method
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => $dataConfig]
        )->run();

        // Place Order
        $this->webposIndex->getCheckoutCartFooter()->waitForElementVisible('.checkout');
        $this->webposIndex->getCheckoutCartFooter()->getButtonCheckout()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();

        $this->webposIndex->getCheckoutPaymentMethod()->getCashOnDeliveryMethod()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();

        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\PlaceOrderSetShipAndCreateInvoiceSwitchStep',
            [
                'createInvoice' => $createInvoice,
                'shipped' => $shipped
            ]
        )->run();

        $this->webposIndex->getCheckoutPlaceOrder()->getButtonPlaceOrder()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();

        //Assert Place Order Success
        $this->assertWebposCheckoutPagePlaceOrderPageSuccessVisible->processAssert($this->webposIndex);

        $orderId = str_replace('#' , '', $this->webposIndex->getCheckoutSuccess()->getOrderId()->getText());

        $this->webposIndex->getCheckoutSuccess()->getNewOrderButton()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();

        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        $this->webposIndex->getCMenu()->ordersHistory();

        sleep(2);
        $this->webposIndex->getOrderHistoryOrderList()->waitLoader();

        $this->webposIndex->getOrderHistoryOrderList()->getFirstOrder()->click();
        while (strcmp($this->webposIndex->getOrderHistoryOrderViewHeader()->getStatus(), 'Not Sync') == 0) {}
        self::assertEquals(
            $orderId,
            $this->webposIndex->getOrderHistoryOrderViewHeader()->getOrderId(),
            "Order Content - Order Id is wrong"
            . "\nExpected: " . $orderId
            . "\nActual: " . $this->webposIndex->getOrderHistoryOrderViewHeader()->getOrderId()
        );

        $this->webposIndex->getOrderHistoryOrderViewHeader()->getTakePaymentButton()->click();
        $this->webposIndex->getOrderHistoryPayment()->getPaymentMethod('Web POS - Cash In')->click();
        $this->webposIndex->getOrderHistoryPayment()->getSubmitButton()->click();
        sleep(2);
        $this->webposIndex->getModal()->getOkButton()->click();
        //Assert Tax Amount in Order History Payment
        $this->assertPaymentSuccess->processAssert($this->webposIndex);

        // Invoice
        sleep(5);
        $this->webposIndex->getOrderHistoryOrderViewFooter()->getInvoiceButton()->click();
        $this->webposIndex->getOrderHistoryContainer()->waitOrderHistoryInvoiceIsVisible();
        while (!$this->webposIndex->getOrderHistoryInvoice()->isVisible()){}
        $this->webposIndex->getOrderHistoryInvoice()->getSubmitButton()->click();
        $this->webposIndex->getMsWebpos()->waitForModalPopup();
        sleep(2);
        $this->webposIndex->getModal()->getOkButton()->click();

        // Assert Invoice Success
        $this->assertInvoiceSuccess->processAssert($this->webposIndex);
        // Assert Order Status
        $this->webposIndex->getOrderHistoryOrderViewHeader()->waitForProcessingStatusVisisble();
        $this->assertOrderStatus->processAssert($this->webposIndex, 'Processing');

        // Shipment
        if (!$this->webposIndex->getOrderHistoryContainer()->getActionsBox()->isVisible()) {
            $this->webposIndex->getOrderHistoryOrderViewHeader()->getMoreInfoButton()->click();
        }
        $this->webposIndex->getOrderHistoryOrderViewHeader()->getAction('Ship')->click();
        $this->webposIndex->getOrderHistoryContainer()->waitForShipmentPopupIsVisible();
        $this->webposIndex->getOrderHistoryShipment()->getSubmitButton()->click();
        sleep(2);
        $this->webposIndex->getModal()->getOkButton()->click();

        // Assert Shipment Success
        $this->assertShipmentSuccess->processAssert($this->webposIndex);
        // Assert Order Status
        $this->webposIndex->getOrderHistoryOrderViewHeader()->waitForCompleteStatusVisisble();
        $this->assertOrderStatus->processAssert($this->webposIndex, 'Complete');


        // Refund
        if (!$this->webposIndex->getOrderHistoryRefund()->isVisible()) {
            if (!$this->webposIndex->getOrderHistoryContainer()->getActionsBox()->isVisible()) {
                $this->webposIndex->getOrderHistoryOrderViewHeader()->getMoreInfoButton()->click();
            }
            sleep(1);
            $this->webposIndex->getOrderHistoryOrderViewHeader()->getAction('Refund')->click();
            sleep(2);
        }
        $this->webposIndex->getOrderHistoryContainer()->waitForRefundPopupIsVisible();
        $this->webposIndex->getOrderHistoryRefund()->getItemQtyToRefundInput($products[0]['product']->getAssociated()['products'][0]->getName())->setValue(3);
        $this->webposIndex->getOrderHistoryRefund()->getItemQtyToRefundInput($products[0]['product']->getAssociated()['products'][1]->getName())->setValue(1);
        $this->webposIndex->getOrderHistoryRefund()->getItemQtyToRefundInput($products[0]['product']->getAssociated()['products'][2]->getName())->setValue(2);
        $this->webposIndex->getOrderHistoryRefund()->getSubmitButton()->click();
        sleep(2);
        $this->webposIndex->getMsWebpos()->waitForModalPopup();
        $this->webposIndex->getModal()->getOkButton()->click();

        // Assert Refund Success
        $this->assertRefundSuccess->processAssert($this->webposIndex, 'Closed');
        // Assert Order Status
        $this->webposIndex->getOrderHistoryOrderViewHeader()->waitForClosedStatusVisisble();
        $this->assertOrderStatus->processAssert($this->webposIndex, 'Closed');


        return [
            'products' => $products
        ];
    }
}