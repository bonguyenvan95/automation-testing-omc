<?php
/**
 * Created by PhpStorm.
 * User: vinh
 * Date: 25/01/2018
 * Time: 09:36
 */

namespace Magento\Webpos\Test\TestCase\OrdersHistory\PaymentShippingMethod;


use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Constraint\Checkout\CheckGUI\AssertWebposCheckoutPagePlaceOrderPageSuccessVisible;
use Magento\Webpos\Test\Page\WebposIndex;

class WebposOHCheckoutWithMultiPaymentMethodTest extends Injectable
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
	 * @var AssertWebposCheckoutPagePlaceOrderPageSuccessVisible
	 */
	protected $assertWebposCheckoutPagePlaceOrderPageSuccessVisible;

	public function __prepare(FixtureFactory $fixtureFactory)
	{
		// Config: use system value for all field in Tax Config
		$this->objectManager->getInstance()->create(
			'Magento\Config\Test\TestStep\SetupConfigurationStep',
			['configData' => 'default_tax_configuration_use_system_value']
		)->run();
		$this->objectManager->getInstance()->create(
			'Magento\Config\Test\TestStep\SetupConfigurationStep',
			['configData' => 'default_payment_method_all_payment']
		)->run();
	}

	public function __inject(
		WebposIndex $webposIndex,
		FixtureFactory $fixtureFactory,
		AssertWebposCheckoutPagePlaceOrderPageSuccessVisible $assertWebposCheckoutPagePlaceOrderPageSuccessVisible
	)
	{
		$this->webposIndex = $webposIndex;
		$this->fixtureFactory = $fixtureFactory;
		$this->assertWebposCheckoutPagePlaceOrderPageSuccessVisible = $assertWebposCheckoutPagePlaceOrderPageSuccessVisible;
	}

	public function test(
		$products = null,
		$paymentMethods
	)
	{

		// Login webpos
		$staff = $this->objectManager->getInstance()->create(
			'Magento\Webpos\Test\TestStep\LoginWebposStep'
		)->run();

		// Create products
		$products = $this->objectManager->getInstance()->create(
			'Magento\Webpos\Test\TestStep\CreateNewProductsStep',
			['products' => $products]
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

		if (!$this->webposIndex->getCheckoutShippingMethod()->getFlatRateFixed()->isVisible()) {
			$this->webposIndex->getCheckoutShippingMethod()->clickShipPanel();
		}
		$this->webposIndex->getCheckoutShippingMethod()->getFlatRateFixed()->click();
		$this->webposIndex->getMsWebpos()->waitCheckoutLoader();

		// Add Payment
		$paymentMethods = $this->objectManager->getInstance()->create(
			'Magento\Webpos\Test\TestStep\AddPaymentOnCheckoutPageStep',
			['paymentMethods' => $paymentMethods]
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

		return [
			'products' => $products,
			'paymentMethods' => $paymentMethods
		];
	}
}