<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/24/2017
 * Time: 4:06 PM
 */

namespace Magento\Storepickup\Test\Block\Adminhtml\Store;

use Magento\Storepickup\Test\Block\Adminhtml\StorepickupGrid;

/**
 * Class StoreGrid
 * @package Magento\Storepickup\Test\Block\Adminhtml\Store
 */
class StoreGrid extends StorepickupGrid
{
    protected $filters = [
        'id' => [
            'selector' => '[name="storepickup_id[]"]',
        ],
        'name' => [
            'selector' => '[name="store_name"]'
        ],
        'address' => [
            'selector' => '[name="address"]'
        ],
        'city' => [
            'selector' => '[name="city"]'
        ],
        'state' => [
            'selector' => '[name="state"]'
        ],
        'country' => [
            'selector' => '[name="country_id"]'
        ],
        'zipcode' => [
            'selector' => '[name="zipcode"]'
        ],
        'status' => [
            'selector' => '[name="status"]'
        ]
    ];
}