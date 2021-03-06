<?php
/**
 * Created by PhpStorm.
 * User: gvt
 * Date: 17/02/2018
 * Time: 19:58
 */

namespace Magento\Webpos\Test\Block\Adminhtml\Locations;

use Magento\Mtf\Client\Locator;
use Magento\Backend\Test\Block\PageActions;

/**
 * Class FormPageActions
 * Form page actions block
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class FormPageActions extends PageActions
{
    /**
     * "Back" button.
     *
     * @var string
     */
    protected $backButton = '#back';

    /**
     * "Reset" button.
     *
     * @var string
     */
    protected $resetButton = '#reset';

    /**
     * "Save and Continue Edit" button.
     *
     * @var string
     */
    protected $saveAndContinueButton = '#saveandcontinue';

    /**
     * "Save" button.
     *
     * @var string
     */
    protected $saveButton = '#save';

    /**
     * "Delete" button.
     *
     * @var string
     */
    protected $deleteButton = '.delete';

    /**
     * Magento new loader.
     *
     * @var string
     */
    protected $spinner = '[data-role="spinner"]';

    /**
     * Magento loader.
     *
     * @var string
     */
    protected $loader = '//ancestor::body/div[@data-role="loader"]';

    /**
     * Magento varienLoader.js loader.
     *
     * @var string
     */
    protected $loaderOld = '//ancestor::body/div[@id="loading-mask"]';

    /**
     * Click "Back" button.
     */
    public function back()
    {
        $this->_rootElement->find($this->backButton)->click();
    }

    /**
     * Click "Reset" button.
     */
    public function reset()
    {
        $this->waitForElementVisible($this->resetButton);
        $this->_rootElement->find($this->resetButton)->click();
    }

    /**
     * Click "Save and Continue Edit" button.
     */
    public function saveAndContinue()
    {
        $this->waitForElementVisible($this->saveAndContinueButton);
        $this->_rootElement->find($this->saveAndContinueButton)->click();
        $this->waitForElementNotVisible('.popup popup-loading');
        $this->waitForElementNotVisible('.loader');
    }

    /**
     * Click "Save" button.
     */
    public function save()
    {
        $this->waitForElementVisible($this->saveButton);
        $this->_rootElement->find($this->saveButton)->click();
        $this->waitForElementNotVisible($this->spinner);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->loaderOld, Locator::SELECTOR_XPATH);
    }

    /**
     * Click "Delete" button.
     */
    public function delete()
    {
        $this->waitForElementNotVisible($this->spinner);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->loaderOld, Locator::SELECTOR_XPATH);
        $this->waitForElementVisible($this->deleteButton);
        $this->_rootElement->find($this->deleteButton)->click();
    }

    public function deleteButton()
    {
        $this->waitForElementNotVisible($this->spinner);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->loaderOld, Locator::SELECTOR_XPATH);
        return $this->_rootElement->find('#delete');
    }

    /**
     * Check 'Delete' button availability.
     *
     * @return bool
     */
    public function checkDeleteButton()
    {
        return $this->_rootElement->find($this->deleteButton)->isVisible();
    }

    public function getButtonById($id)
    {
        $this->waitForElementNotVisible($this->spinner);
        $this->waitForElementNotVisible($this->loader, Locator::SELECTOR_XPATH);
        $this->waitForElementNotVisible($this->loaderOld, Locator::SELECTOR_XPATH);
        return $this->_rootElement->find('#' . $id);
    }

    public function getButtonByname($name)
    {
        return $this->_rootElement->find('//div[@class="page-actions-buttons"]//button//span[text()="' . $name . '"]', Locator::SELECTOR_XPATH);
    }

    public function getTitleByName($name)
    {
        return $this->_rootElement->find('//div[@class="page-actions-inner"][@data-title="' . $name . '"]', Locator::SELECTOR_XPATH);
    }
}
