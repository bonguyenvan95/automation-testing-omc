<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 1/8/2018
 * Time: 4:31 PM
 */

namespace Magento\Webpos\Test\Constraint\Tax;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Webpos\Test\Page\WebposIndex;


class AssertTaxAmountOnOrderPage extends AbstractConstraint
{
//
//    public function processAssert($taxRate, WebposIndex $webposIndex)
//    {
//        $taxRate = (float) $taxRate / 100;
//        $subtotalOnPage = $webposIndex->getCheckoutCartFooter()->getGrandTotalItemPrice("Subtotal")->getText();
//        $subtotalOnPage = (float)substr($subtotalOnPage,1);
//        $taxAmount = $subtotalOnPage * $taxRate;
//        $taxAmountOnPage = $webposIndex->getOr()->getGrandTotalItemPrice("Tax")->getText();
//        $taxAmountOnPage = (float)substr($taxAmountOnPage,1);
//
//        \PHPUnit_Framework_Assert::assertEquals(
//            $taxAmount,
//            $taxAmountOnPage,
//            'On the Cart - The Tax at the web POS was not correctly.'
//        );
//    }
//
    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "The Tax at the web POS was correctly.";
    }
}