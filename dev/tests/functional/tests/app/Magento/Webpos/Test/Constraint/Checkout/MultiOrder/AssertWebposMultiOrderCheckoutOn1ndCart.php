<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12/12/2017
 * Time: 13:23
 */

namespace Magento\Webpos\Test\Constraint\Checkout\MultiOrder;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Webpos\Test\Page\WebposIndex;
/**
 * Class AssertWebposMultiOrderCheckoutOn1ndCart
 * @package Magento\AssertWebposCheckGUICustomerPriceCP54\Test\Constraint\CategoryRepository\MultiOrder
 */
class AssertWebposMultiOrderCheckoutOn1ndCart extends AbstractConstraint
{
    public function processAssert(WebposIndex $webposIndex, $secondCart, $orderNumber)
    {
        \PHPUnit_Framework_Assert::assertFalse(
            $webposIndex->getCheckoutCartHeader()->getMultiOrderItem($orderNumber)->isVisible(),
            'On the AssertWebposCheckGUICustomerPriceCP54 TaxClass Header - The cart item with name\'s'.$orderNumber.' was visible.'
        );
        \PHPUnit_Framework_Assert::assertTrue(
            $webposIndex->getCheckoutCartHeader()->getMultiOrderItem($secondCart)->isVisible(),
            'On the AssertWebposCheckGUICustomerPriceCP54 TaxClass Header - The cart item with name\'s'.$secondCart.' was visible.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "On the AssertWebposMultiOrderCheckoutOn1ndCart TaxClass Header - The cart item were visible correctly.";
    }
}