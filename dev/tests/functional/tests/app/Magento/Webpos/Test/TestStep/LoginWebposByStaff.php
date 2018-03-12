<?php
/**
 * Created by PhpStorm.
 * User: Bang
 * Date: 3/12/2018
 * Time: 9:25 AM
 */

namespace Magento\Webpos\Test\TestStep;

use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Webpos\Test\Fixture\Location;
use Magento\Webpos\Test\Fixture\Pos;
use Magento\Webpos\Test\Fixture\Staff;
use Magento\Webpos\Test\Page\WebposIndex;

class LoginWebposByStaff implements TestStepInterface
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * @var Staff
     */
    protected $staff;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Pos
     */
    protected $pos;

    public function __construct(
        WebposIndex $webposIndex,
        Staff $staff,
        Location $location = null,
        Pos $pos = null
    ) {
        $this->webposIndex = $webposIndex;
        $this->staff = $staff;
        $this->location = $location;
        $this->pos = $pos;
    }

    public function run()
    {
        $username = $this->staff->getUsername();
        $password = $this->staff->getPassword();
        $this->webposIndex->open();
        $this->webposIndex->getMsWebpos()->waitForElementNotVisible('.loading-mask');
        if ($this->webposIndex->getLoginForm()->isVisible()) {
            $this->webposIndex->getLoginForm()->getUsernameField()->setValue($username);
            $this->webposIndex->getLoginForm()->getPasswordField()->setValue($password);
            $this->webposIndex->getLoginForm()->clickLoginButton();
            $this->webposIndex->getMsWebpos()->waitForElementNotVisible('.loading-mask');
            $this->webposIndex->getMsWebpos()->waitForElementVisible('[id="webpos-location"]');
            if ($this->location) {
                $this->webposIndex->getLoginForm()->setLocation($this->location->getDisplayName());
            }
            if ($this->pos) {
                $this->webposIndex->getLoginForm()->setPos($this->pos->getPosName());
            }
            if ($this->location || $this->pos) {
                $this->webposIndex->getLoginForm()->getEnterToPos()->click();
            }
            $this->webposIndex->getMsWebpos()->waitForElementNotVisible('.loading-mask');
            $this->webposIndex->getMsWebpos()->waitForSyncDataVisible();
            $time = time();
            $timeAfter = $time + 360;
            while ($this->webposIndex->getFirstScreen()->isVisible() && $time < $timeAfter){
                $time = time();
            }
            sleep(2);
        }
        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
//        $this->webposIndex->getMsWebpos()->waitCartLoader();
        if ($this->location || $this->pos) {
            $this->webposIndex->getOpenSessionPopup()->getOpenSessionButton()->click();
            $this->webposIndex->getMsWebpos()->waitForElementNotVisible('[id="popup-open-shift"]');
        }

    }
}