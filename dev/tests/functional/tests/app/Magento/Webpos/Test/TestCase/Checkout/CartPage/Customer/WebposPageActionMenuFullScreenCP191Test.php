<?php
/**
 * Created by PhpStorm.
 * User: bang
 * Date: 09/01/2018
 * Time: 10:07
 */
namespace Magento\Webpos\Test\TestCase\Checkout\CartPage\Customer;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;
class WebposPageActionMenuFullScreenCP191Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    public function __prepare()
    {
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'webpos_default_guest_checkout_rollback']
        )->run();
    }

    public function __inject
    (
        WebposIndex $webposIndex
    )
    {
        $this->webposIndex = $webposIndex;
    }

    public function test($products)
    {

        //Create product
        $product = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\CreateNewProductsStep',
            ['products' => $products]
        )->run()[0]['product'];

        //Login webpos
        $staff = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\LoginWebposStep'
        )->run();

        //Add product to cart
        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getCheckoutProductList()->search($product->getName());
        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getMsWebpos()->waitCartLoader();

        //Checkout
        $this->webposIndex->getCheckoutCartFooter()->getButtonCheckout()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();

        //Click ... Menu > click Enter/full screen
        $minHeightBeforeFull = $this->webposIndex->getBody()->getPageStyleMinHeight();

        $this->webposIndex->getCheckoutCartHeader()->getIconActionMenu()->click();
        $this->webposIndex->getCheckoutFormAddNote()->waitFullScreenMode();
        $this->webposIndex->getCheckoutFormAddNote()->getFullScreenMode()->click();        sleep(1);
        $minHeightAfterFull = $this->webposIndex->getBody()->getPageStyleMinHeight();

        //Click again Enter/full screen
//        $this->webposIndex->getCheckoutCartHeader()->getIconActionMenu()->click();
//        $this->webposIndex->getCheckoutFormAddNote()->waitFullScreenMode();
//        $this->webposIndex->getCheckoutFormAddNote()->getFullScreenMode()->click();
//        sleep(1);
//        $minHeightAfterAgain = $this->webposIndex->getBody()->getPageStyleMinHeight();

        return ['minHeightBeforeFull' => $minHeightBeforeFull,
            'minHeightAfterFull' => $minHeightAfterFull,
//            'minHeightAfterAgain' => $minHeightAfterAgain
            ];
    }
}