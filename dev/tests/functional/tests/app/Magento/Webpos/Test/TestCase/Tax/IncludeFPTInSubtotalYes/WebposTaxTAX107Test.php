<?php
/**
 * Created by PhpStorm.
 * User: vong
 * Date: 1/16/2018
 * Time: 1:09 PM
 */

namespace Magento\Webpos\Test\TestCase\Tax\IncludeFPTInSubtotalYes;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Tax\Test\Fixture\TaxRule;
use Magento\Webpos\Test\Constraint\Checkout\CheckGUI\AssertWebposCheckoutPagePlaceOrderPageSuccessVisible;
use Magento\Webpos\Test\Constraint\Tax\AssertProductPriceWithCatalogPriceInCludeTaxAndEnableCrossBorderTrade;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountNoApplyTaxToFpt;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountOnCartPageAndCheckoutPage;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountOnCartPageAndCheckoutPageWithApplyDiscountOnPriceExcludingTax;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountOnCartPageAndCheckoutPageWithApplyDiscountOnPriceIncludingTax;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountWithApplyTaxOnCustomPrice;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountWithIncludeFptInSubtotal;
use Magento\Webpos\Test\Page\WebposIndex;

class WebposTaxTAX107Test extends Injectable
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
     * @var TaxRule $caTaxRule
     */
    protected $caTaxRule;

    /**
     * @var AssertTaxAmountWithIncludeFptInSubtotal
     */
    protected $assertTaxAmountWithIncludeFptInSubtotal;

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
        // Config system value
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'default_tax_configuration_use_system_value']
        )->run();

        // Change TaxRate
        $miTaxRate = $fixtureFactory->createByCode('taxRate', ['dataset'=> 'US-MI-Rate_1']);
        $this->objectManager->create('Magento\Tax\Test\Handler\TaxRate\Curl')->persist($miTaxRate);

        // Add Customer
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'customer_MI']);
        $customer->persist();

        return [
            'customer' => $customer,
            'taxRate' => $miTaxRate->getRate()
        ];
    }


    /**
     * @param WebposIndex $webposIndex
     * @param FixtureFactory $fixtureFactory
     */
    public function __inject(
        WebposIndex $webposIndex,
        FixtureFactory $fixtureFactory,
        AssertTaxAmountWithIncludeFptInSubtotal $assertTaxAmountWithIncludeFptInSubtotal,
        AssertWebposCheckoutPagePlaceOrderPageSuccessVisible $assertWebposCheckoutPagePlaceOrderPageSuccessVisible
    )
    {
        $this->webposIndex = $webposIndex;
        $this->fixtureFactory = $fixtureFactory;
        $this->assertTaxAmountWithIncludeFptInSubtotal = $assertTaxAmountWithIncludeFptInSubtotal;
        $this->assertWebposCheckoutPagePlaceOrderPageSuccessVisible = $assertWebposCheckoutPagePlaceOrderPageSuccessVisible;
    }

    /**
     * @param Customer $customer
     * @param $products
     * @param $shippingTaxRate
     */
    public function test(
        Customer $customer,
        $products,
        $taxRate
    )
    {
        // Create products
        $products = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\CreateNewProductsStep',
            ['products' => $products]
        )->run();
        // Config [Include FPT In Subtotal] = Yes and [Enable FPT] = Yes
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'include_FPT_in_subtotal_and_enable_fpt']
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
        // Change customer in cart meet Michigan Tax and FPT tax
        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\ChangeCustomerOnCartStep',
            ['customer' => $customer]
        )->run();
        sleep(2);
        $actualTaxAmount = substr($this->webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice('Tax')->getText(), 1);
        $actualSubtotal = substr($this->webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice('Subtotal')->getText(), 1);
        $actualGrandtotal = substr($this->webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice('Total')->getText(), 1);
        $this->assertTaxAmountWithIncludeFptInSubtotal
            ->processAssert($products[0]['product']->getPrice(), $taxRate, $products[0]['product']->getFpt()[0]['price'], $actualTaxAmount, $actualSubtotal, $actualGrandtotal);
        $this->webposIndex->getCheckoutCartFooter()->getButtonCheckout()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        $actualTaxAmount = substr($this->webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice('Tax')->getText(), 1);
        $actualSubtotal = substr($this->webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice('Subtotal')->getText(), 1);
        $actualGrandtotal = substr($this->webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice('Total')->getText(), 1);
        $this->assertTaxAmountWithIncludeFptInSubtotal
            ->processAssert($products[0]['product']->getPrice(), $taxRate, $products[0]['product']->getFpt()[0]['price'], $actualTaxAmount, $actualSubtotal, $actualGrandtotal);
        $this->webposIndex->getCheckoutPaymentMethod()->getCashInMethod()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\PlaceOrderSetShipAndCreateInvoiceSwitchStep',
            [
                'createInvoice' => true,
                'shipped' => false
            ]
        )->run();
        $this->webposIndex->getCheckoutPlaceOrder()->getButtonPlaceOrder()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        //Assert Place Order Success
        $this->assertWebposCheckoutPagePlaceOrderPageSuccessVisible->processAssert($this->webposIndex);

    }

    public function tearDown()
    {
        // Config system value
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'default_tax_configuration_use_system_value']
        )->run();
    }
}