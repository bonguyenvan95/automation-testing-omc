<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 3/14/2018
 * Time: 8:01 AM
 */

namespace Magento\Webpos\Test\TestCase\SessionManagementValidate\SetClosingBalance;

use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;
use Magento\Webpos\Test\Fixture\Denomination;
use Magento\Webpos\Test\Constraint\SessionManagement\AssertSetReasonPopup;

/**
 * Class WebposSessionManagementValidateSM33Test
 * @package Magento\Webpos\Test\TestCase\SessionManagementValidate\SetClosingBalance
 */
class WebposSessionManagementValidateSM33Test extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * @var AssertSetReasonPopup
     */
    protected $assertSetReasonPopup;

    /**
     * @param WebposIndex $webposIndex
     * @param AssertSetReasonPopup $assertSetReasonPopup
     */
    public function __inject(
        WebposIndex $webposIndex,
        AssertSetReasonPopup $assertSetReasonPopup
    ) {
        $this->webposIndex = $webposIndex;
        $this->assertSetReasonPopup = $assertSetReasonPopup;
    }

    /**
     * @param Denomination $denomination
     */
    public function test(
        Denomination $denomination
    ) {
        // Precondition
        $denomination->persist();

        // Config create session before working
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'create_section_before_working_yes']
        )->run();

        // Login webpos
        $staff = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\LoginWebposChooseLocationStep'
        )->run();

        // Open session
        $this->webposIndex->getMsWebpos()->waitForElementVisible('[id="popup-open-shift"]');
        $this->webposIndex->getOpenSessionPopup()->waitForElementNotVisible('[data-bind="visible:loading"]');
        $this->webposIndex->getOpenSessionPopup()->setCoinBillValue($denomination->getDenominationName());
        $this->webposIndex->getOpenSessionPopup()->getNumberOfCoinsBills()->setValue(10);

        $this->webposIndex->getOpenSessionPopup()->getOpenSessionButton()->click();
        sleep(1);
        $this->webposIndex->getSessionShift()->getSetClosingBalanceButton()->click();
        sleep(1);
        $this->webposIndex->getSessionSetClosingBalancePopup()->getConfirmButton()->click();
        $this->webposIndex->getSessionConfirmModalPopup()->getOkButton()->click();

        // Assert Set Reason popup
        $this->assertSetReasonPopup->processAssert($this->webposIndex);

        $this->webposIndex->getSessionSetReasonPopup()->getCancelButton()->click();

        // Assert Set Reason popup not visible
        $this->assertFalse(
            $this->webposIndex->getSessionSetReasonPopup()->isVisible(),
            'Set Reason Popup is visible.'
        );

        // Assert [End of session] button changes into [Validate Closing] button
        $this->assertEquals(
            'Validate Closing',
            $this->webposIndex->getSessionShift()->getButtonEndSession()->getText(),
            'Button "Validate Closing" is not visible.'
        );

        sleep(2);
        // End session
        $this->webposIndex->getSessionShift()->getButtonEndSession()->click();
        $this->webposIndex->getSessionShift()->waitForElementNotVisible('.btn-close-shift');
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->objectManager->getInstance()->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'create_section_before_working_no']
        )->run();
    }
}