<?php
/**
 * Created by PhpStorm.
 * User: Bang
 * Date: 1/11/2018
 * Time: 2:58 PM
 */

namespace Magento\Webpos\Test\TestCase\Checkout\CartPage\CustomSale;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;
/**
 *  * Preconditions:
 * 1. Login webpos by a  staff
 * 2. Add some products  to cart
 * 3. Add discount (type :%)
 *
 * Step:
 * 1. Add a custom product to cart
 *
 */

/**
 * Class WebposCustomSaleCustomProductWithDiscountCP78EntityTest
 * @package Magento\Webpos\Test\TestCase\Checkout\CartPage\CustomSale
 */
class WebposCustomSaleCustomProductWithDiscountCP78EntityTest extends  Injectable
{
    /**
     * @var WebposIndex $webposIndex
     */
    protected $webposIndex;

    /**
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
     * @param $productName
     * @param $productDescription
     */
    public function test($productName, $price, $amountValue)
    {
        $staff = $this->objectManager->create(
            '\Magento\Webpos\Test\TestStep\LoginWebposStep'
        )->run();

        $this->webposIndex->getCheckoutProductList()->getCustomSaleButton()->click();
        $this->webposIndex->getCheckoutCustomSale()->getProductNameInput()->setValue($productName);
        // number field price keyboard
        $this->webposIndex->getCheckoutCustomSale()->getProductPriceInput()->setValue($price);
        sleep(2);
        $this->webposIndex->getCheckoutCustomSale()->getAddToCartButton()->click();

        $this->webposIndex->getCheckoutCartItems()->getCartItem($productName)->click();
        $this->webposIndex->getCheckoutProductEdit()->getDiscountButton()->click();
        $this->webposIndex->getCheckoutProductEdit()->getPercentButton()->click();
        $this->webposIndex->getCheckoutProductEdit()->getAmountInput()->setValue($amountValue);
        sleep(3);
        $this->webposIndex->getMsWebpos()->clickOutsidePopup();

    }
}