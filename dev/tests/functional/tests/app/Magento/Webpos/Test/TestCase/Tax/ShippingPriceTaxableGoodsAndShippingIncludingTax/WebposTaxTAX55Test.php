<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 1/15/2018
 * Time: 10:38 AM
 */

namespace Magento\Webpos\Test\TestCase\Tax\ShippingPriceTaxableGoodsAndShippingIncludingTax;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Tax\Test\Fixture\TaxRule;
use Magento\Webpos\Test\Constraint\Tax\AssertTaxAmountOnCartPageAndCheckoutPageWithShippingFee;
use Magento\Webpos\Test\Page\WebposIndex;


/**
 * Class WebposTaxTAX55Test
 * @package Magento\Webpos\Test\TestCase\Tax
 */
class WebposTaxTAX55Test extends Injectable
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
     * @var TaxRule $taxRuleCA
     */
    protected $taxRuleCA;

    /**
     * @var AssertTaxAmountOnCartPageAndCheckoutPageWithShippingFee
     */
    protected $assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee;

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
        $taxRateMI = $fixtureFactory->createByCode('taxRate', ['dataset' => 'US-MI-Rate_1']);
        $this->objectManager->create('Magento\Tax\Test\Handler\TaxRate\Curl')->persist($taxRateMI);

        // Change TaxRate
        $taxRateCA = $fixtureFactory->createByCode('taxRate', ['dataset' => 'US-CA-Rate_1']);
        $this->objectManager->create('Magento\Tax\Test\Handler\TaxRate\Curl')->persist($taxRateCA);

        $taxRates = [
          'taxRateMI' => $taxRateMI,
          'taxRateCA' => $taxRateCA
        ];

        // Create CA Tax Rule
        $taxRule = $fixtureFactory->createByCode('taxRule', ['dataset'=> 'CA_rule']);
        $taxRule->persist();
        $this->taxRuleCA = $taxRule;

        // Add Customer
        $customer = $fixtureFactory->createByCode('customer', ['dataset' => 'customer_MI']);
        $customer->persist();

        return [
            'customer' => $customer,
            'taxRates' => $taxRates
        ];
    }

    /**
     * @param WebposIndex $webposIndex
     * @param FixtureFactory $fixtureFactory
     * @param AssertTaxAmountOnCartPageAndCheckoutPageWithShippingFee $assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee
     */
    public function __inject(
        WebposIndex $webposIndex,
        FixtureFactory $fixtureFactory,
        AssertTaxAmountOnCartPageAndCheckoutPageWithShippingFee $assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee
    )
    {
        $this->webposIndex = $webposIndex;
        $this->fixtureFactory = $fixtureFactory;
        $this->assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee = $assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee;
    }

    /**
     * @param Customer $customer
     * @param $products
     * @param $configData
     * @param $taxRates
     * @param bool $createInvoice
     * @param bool $shipped
     * @return array
     */
    public function test(
        Customer $customer,
        $products,
        $configData,
        $taxRates,
        $createInvoice = true,
        $shipped = false
    )
    {
        // Create products
        $products = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\CreateNewProductsStep',
            ['products' => $products]
        )->run();

        // Config
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => $configData]
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

        // Change customer in cart
        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\ChangeCustomerOnCartStep',
            ['customer' => $customer]
        )->run();

        // Check out
        $this->webposIndex->getCheckoutCartFooter()->getButtonCheckout()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        sleep(1);

        // Select Shipping Method
        $this->webposIndex->getCheckoutShippingMethod()->openCheckoutShippingMethod();
        $this->webposIndex->getCheckoutShippingMethod()->getFlatRateFixed()->click();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        sleep(1);
        $shippingFee = $this->webposIndex->getCheckoutShippingMethod()->getShippingMethodPrice("Flat Rate - Fixed")->getText();
        $shippingFee = (float)substr($shippingFee, 1);

        //Assert Tax Amount on Checkout Page
        $this->assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee->processAssert($taxRates['taxRateMI']->getRate(), $shippingFee, $this->webposIndex);
        //End Assert Tax Amount on Checkout Page

        //Change customer address to California
        $this->webposIndex->getCheckoutCartHeader()->getCustomerTitleDefault()->click();
        sleep(1);
        $this->webposIndex->getCheckoutEditCustomer()->getEditShippingAddressIcon()->click();
        sleep(1);
        $this->webposIndex->getCheckoutEditAddress()->getRegionId()->setValue('California');
        sleep(1);
        $this->webposIndex->getCheckoutEditAddress()->getSaveButton()->click();
        sleep(1);
        $this->webposIndex->getCheckoutEditCustomer()->getSaveButton()->click();
        $this->webposIndex->getToaster()->getWarningMessage();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        sleep(3);
        //Assert Tax Amount on Checkout Page
        $this->assertTaxAmountOnCartPageAndCheckoutPageWithShippingFee->processAssert($taxRates['taxRateCA']->getRate(), $shippingFee, $this->webposIndex);
        //End Assert Tax Amount on Checkout Page

        return [
            'products' => $products,
            'taxRates' => $taxRates
        ];
    }

    /**
     *
     */
    public function tearDown()
    {
        // Config system value
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'default_tax_configuration_use_system_value']
        )->run();

        // Delete Rax Rule
        $this->objectManager->create('Magento\Webpos\Test\Handler\TaxRule\Curl')->persist($this->taxRuleCA);
    }
}