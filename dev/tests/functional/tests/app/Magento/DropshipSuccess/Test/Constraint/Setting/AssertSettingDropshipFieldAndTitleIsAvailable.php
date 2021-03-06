<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 05/12/2017
 * Time: 10:31
 */

namespace Magento\DropshipSuccess\Test\Constraint\Setting;

use Magento\FulfilReport\Test\Page\Adminhtml\ReportIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
/**
 * Class AssertSettingDropshipFieldAndTitleIsAvailable
 * @package Magento\DropshipSuccess\Test\Constraint\Setting
 */
class AssertSettingDropshipFieldAndTitleIsAvailable extends AbstractConstraint
{
    /**
     * @param ReportIndex $reportIndex
     */
    public function processAssert(ReportIndex $reportIndex, $titleDropshipSection)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $titleDropshipSection,
            $reportIndex->getContainer()->getTitleDropshipSection()->getText(),
            'Title Order Section on the DROP SHIP CONFIGURATION fulfilment was not visible.'
        );
        \PHPUnit_Framework_Assert::assertTrue(
            $reportIndex->getContainer()->getFirstFieldDropship()->isVisible(),
            'On The Backend Page, the Field Section DROP SHIP CONFIGURATION Of the Extension was not visible.'
        );
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'On The Backend Page, all the elements include: fields and titles in the DROPSHIP configuration Of the Fulfilment Extension Fulfilment was visible successfully.';
    }
}