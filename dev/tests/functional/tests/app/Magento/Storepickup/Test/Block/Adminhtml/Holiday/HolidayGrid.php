<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/24/2017
 * Time: 4:06 PM
 */

namespace Magento\Storepickup\Test\Block\Adminhtml\Holiday;

use Magento\Storepickup\Test\Block\Adminhtml\StorepickupGrid;

/**
 * Class HolidayGrid
 * @package Magento\Storepickup\Test\Block\Adminhtml\Holiday
 */
class HolidayGrid extends StorepickupGrid
{
    protected $filters = [
        'id' => [
            'selector' => '[name="holiday_id[from]"]'
        ],
        'name' => [
            'selector' => '[name="holiday_name"]'
        ],
        'comment' => [
            'selector' => '[name="holiday_comment"]'
        ]
    ];
}