<?php
/**
 * Created by PhpStorm.
 * User: gvt
 * Date: 12/02/2018
 * Time: 09:14
 */
namespace Magento\Webpos\Test\TestCase\Location\EditLocation;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\Adminhtml\LocationIndex;
use Magento\Webpos\Test\Fixture\Location;
use Magento\Webpos\Test\Page\Adminhtml\LocationNews;


class WebposManageLocationML32Test extends Injectable
{
    /**
     * Webpos Location Index page.
     *
     * @var LocationIndex
     */
    private $locationIndex;

    /**
     * @var LocationNews
     */
    private $locationNews;
    /**
     * Inject location pages.
     *
     * @param LocationIndex
     * @return void
     */
    public function __inject(
        LocationIndex $locationIndex,
        LocationNews $locationNews
    ) {
        $this->locationIndex = $locationIndex;
        $this->locationNews = $locationNews;
    }

    public function test(Location $location)
    {
        // Steps
        $location->persist();
        $this->locationIndex->open();
        $this->locationIndex->getLocationsGrid()->resetFilter();
        $this->locationIndex->getLocationsGrid()->search(['description' => $location->getDescription()]);
        $this->locationIndex->getLocationsGrid()->getRowByDisplayName($location->getDescription())->find('.action-menu-item')->click();
        sleep(1);
        $this->locationNews->getFormPageActionsLocation()->deleteButton()->click();
        $this->locationNews->getModalsWrapper()->getOkButton()->click();
        sleep(1);
    }
}

