<?php
/**
 * Created by PhpStorm.
 * User: bang
 * Date: 26/01/2018
 * Time: 13:26
 */
namespace Magento\Webpos\Test\TestCase\OnHoldOrder\ProcessingOnHoldOrder;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;

class WebposOnHoldOrderONH16Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

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

        //Create a on-hold-order
            //Add a product to cart
        $this->webposIndex->getCheckoutProductList()->search($product->getName());
        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        sleep(1);
            //Hold
        $this->webposIndex->getCheckoutCartFooter()->getButtonHold()->click();
        $this->webposIndex->getMsWebpos()->waitCartLoader();
        $this->webposIndex->getMsWebpos()->waitCheckoutLoader();
        sleep(1);

        //Click on On-hold Orders menu
        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        $this->webposIndex->getCMenu()->onHoldOrders();
        sleep(1);
        $orderId = $this->webposIndex->getOnHoldOrderOrderList()->getIdFirstOrder();
        $this->webposIndex->getOnHoldOrderOrderList()->waitLoader();
        $this->webposIndex->getOnHoldOrderOrderViewFooter()->getDeleteButton()->click();
        sleep(1);

        return ['orderId' => $orderId];
    }
}