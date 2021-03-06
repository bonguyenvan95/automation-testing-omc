<?php
/**
 * Created by PhpStorm.
 * User: finbert
 * Date: 08/05/2018
 * Time: 15:16
 */

namespace Magento\Webpos\Test\TestCase\Staff\CheckFilterFunction;


use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\Adminhtml\StaffIndex;

/**
 * Class ManageStaffMS11Test
 *
 * Precondition: Exist at least 2 records on the grid
 * 1. Go to backend > Sales > Manage Staffs
 *
 * Steps:
 * "1. Click on [Filters] button
 * 2. Input data into some fields that dont match any record
 * 3. Click on [Apply Filters] button"
 *
 * Acceptance:
 * - Close Filter form
 * - Show message: ""We couldn't find any records."" on the Grid"
 *
 * @package Magento\Webpos\Test\TestCase\Staff\CheckFilterFunction
 */
class ManageStaffMS11Test extends Injectable
{
    /**
     * Staff Index Page
     * @var StaffIndex
     */
    protected $staffIndex;

    public function __inject(
        StaffIndex $staffIndex
    ){
        $this->staffIndex = $staffIndex;
    }

    public function test($username, $email, $display_name){
        $this->staffIndex->open();
        $this->staffIndex->getStaffsGrid()->waitLoader();
        $this->staffIndex->getStaffsGrid()->getFilterButton()->click();
        $this->staffIndex->getStaffsGrid()->resetFilter();
        $this->staffIndex->getStaffsGrid()->search([
            'username' => $username,
            'email' => $email,
            'display_name' => $display_name
        ]);
        sleep(1);
    }

}