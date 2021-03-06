<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 06/12/2017
 * Time: 09:47
 */

namespace Magento\Webpos\Test\Constraint\Checkout\CartPageActionMenu;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Webpos\Test\Page\WebposIndex;
/**
 * Class AssertWebposEnterFullScreen
 * @package Magento\AssertWebposCheckGUICustomerPriceCP54\Test\Constraint\CategoryRepository\CartPageActionMenu
 */
class AssertWebposEnterFullScreen extends AbstractConstraint
{
    public function processAssert(WebposIndex $webposIndex, $minHeightBefore)
    {
        $minHeightAfter = $webposIndex->getBody()->getPageStyleMinHeight();
        if($minHeightAfter > $minHeightBefore)
            $tag = 'Full';
        else
            $tag = 'None';
        \PHPUnit_Framework_Assert::assertEquals('Full',$tag,
            'Not enter full screen');
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "On the CategoryRepository Page - Products List Page - All the action CLOSE ORDER NOTE And SAVE ORDER NOTE, TEXTAREA at the web POS TaxClass were visible successfully.";
    }
}