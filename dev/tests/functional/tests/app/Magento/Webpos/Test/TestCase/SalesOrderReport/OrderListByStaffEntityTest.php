<?php

/**
 * @Author: thomas
 * @Created At:   2017-09-12 15:20:10
 * @Email:  thomas@trueplus.vn
 * @Last Modified by:   thomas
 * @Last Modified time: 2017-10-06 11:26:25
 * @Links : https://www.facebook.com/Onjin.Matsui.VTC.NQC
 */

namespace Magento\Webpos\Test\TestCase\SalesOrderReport;

use Magento\Webpos\Test\Page\Adminhtml\OrderListByStaff;
use Magento\Webpos\Test\Fixture\Shift;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create customer.
 * 2. Create product.
 * 3. Place order.
 * 4. Refresh statistic.
 *
 * Steps:
 * 1. Open Backend.
 * 2. Go to Reports > Products > Bestsellers.
 * 3. Select time range, report period.
 * 4. Click "Show report".
 * 5. Perform all assertions.
 *
 * @group Reports_(MX)
 * @ZephyrId MAGETWO-28222
 */
class OrderListByStaffEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'MX';
    /* end tags */

    /**
     * OrderListByStaff page.
     *
     * @var OrderListByStaff
     */
    protected $orderListByStaff;

    /**
     * Inject pages.
     *
     * @param SalesByStaffDaily $orderListByStaff
     * @return void
     */
    public function __inject(OrderListByStaff $orderListByStaff)
    {
        $this->orderListByStaff = $orderListByStaff;
    }

    /**
     * Bestseller Products Report.
     *
     * @param Shift $shift
     * @param array $shifts
     * @return void
     */
    public function test(Shift $shift, array $shifts)
    {
        // Preconditions
//        $shift->persist();
        $this->orderListByStaff->open();
        $this->orderListByStaff->getMessagesBlock()->clickLinkInMessage('notice', 'here');

        // Steps
        $this->orderListByStaff->getFilterBlock()->viewsReport($shifts);
        $this->orderListByStaff->getActionsBlock()->showReport();
    }
}
