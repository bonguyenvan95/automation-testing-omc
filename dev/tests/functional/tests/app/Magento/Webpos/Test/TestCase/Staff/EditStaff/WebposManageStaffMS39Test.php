<?php
/**
 * Created by PhpStorm.
 * User: gvt
 * Date: 12/02/2018
 * Time: 09:14
 */
namespace Magento\Webpos\Test\TestCase\Staff\EditStaff;
use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\Adminhtml\StaffIndex;
use Magento\Webpos\Test\Page\Adminhtml\StaffNews;
use Magento\Mtf\Fixture\FixtureFactory;

class WebposManageStaffMS39Test extends Injectable
{
    /**
     * Webpos Staff Index page.
     *
     * @var StaffIndex
     */
    private $staffsIndex;
    /**
     * @var StaffNews
     */
    private $staffsNew;

    /**
     * Inject Staff pages.
     *
     * @param StaffIndex $staffsIndex
     * @return void
     */
    public function __inject(
        StaffIndex $staffsIndex,
        StaffNews $staffsNew
    ) {
        $this->staffsIndex = $staffsIndex;
        $this->staffsNew = $staffsNew;
    }

    public function test(FixtureFactory $fixtureFactory)
    {
        // Preconditions:
        $staff1 = $fixtureFactory->createByCode('staff', ['dataset' => 'staffMS21']);
        $staff1->persist();
        $staff = $fixtureFactory->createByCode('staff', ['dataset' => 'staffMS21']);
        $staff->persist();
        // Steps
        $this->staffsIndex->open();
        $this->staffsIndex->getStaffsGrid()->search(['email' => $staff->getEmail()]);
        $this->staffsIndex->getStaffsGrid()->getRowByEmail($staff->getEmail())->find('.action-menu-item')->click();
        sleep(1);
        $this->staffsNew->getStaffsForm()->setPassword($staff->getPassword());
        $this->staffsNew->getStaffsForm()->setConfimPassword($staff->getPasswordConfirmation());
        $this->staffsNew->getStaffsForm()->setEmailAddress($staff1->getEmail());
        $this->staffsNew->getFormPageActionsStaff()->save();
        sleep(1);

        return ['message' => 'Email '.$staff1->getEmail().' is existed.'];

    }
}

