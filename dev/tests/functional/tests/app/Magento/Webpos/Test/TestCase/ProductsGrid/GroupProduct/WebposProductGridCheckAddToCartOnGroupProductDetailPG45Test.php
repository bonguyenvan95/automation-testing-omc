<?php
/**
 * Created by PhpStorm.
 * User: Bang
 * Date: 2/22/2018
 * Time: 3:19 PM
 */

namespace Magento\Webpos\Test\TestCase\ProductsGrid\GroupProduct;

use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;

class  WebposProductGridCheckAddToCartOnGroupProductDetailPG45Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    public function __inject(WebposIndex $webposIndex)
    {
        $this->webposIndex = $webposIndex;
    }

    public function test($products)
    {
        // Create products
        $products = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\CreateNewProductsStep',
            ['products' => $products]
        )->run();
        // Login webpos
        $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\SessionInstallStep'
        )->run();


        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getCheckoutProductList()->search($products[0]['product']->getSku());
        $this->webposIndex->getMsWebpos()->waitForElementVisible('[id="popup-product-detail"]');
        sleep(3);         $this->webposIndex->getMsWebpos()->clickOutsidePopup();
        $this->webposIndex->getMsWebpos()->waitForElementNotVisible('[id="popup-product-detail"]');
        $this->webposIndex->getCheckoutProductList()->getFirstProduct()->hover();
        $this->webposIndex->getCheckoutProductList()->getFirstProductDetailButton()->click();
        $this->webposIndex->getMsWebpos()->waitForElementVisible('[id="popup-product-detail"]');
        $this->webposIndex->getCheckoutProductDetail()->getButtonAddToCart()->click();
        sleep(2);
        $childProducts = $products[0]['product']->getAssociated()['products'];
        foreach ($childProducts as $childProduct) {
            $productName = $childProduct->getName();
            $this->assertTrue(
                $this->webposIndex->getCheckoutProductDetail()->getGroupProductItemQtyMessageError($productName)->isVisible(),
                "Qty message error of product '" . $productName . "' is not visible"
            );
        }

    }
}