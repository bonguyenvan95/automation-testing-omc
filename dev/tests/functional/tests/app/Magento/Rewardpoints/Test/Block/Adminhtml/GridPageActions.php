<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/24/2017
 * Time: 3:58 PM
 */

namespace Magento\Rewardpoints\Test\Block\Adminhtml;

use Magento\Backend\Test\Block\PageActions;
use Magento\Mtf\Client\Locator;

/**
 * Class GridPageActions
 * @package Magento\Rewardpoints\Test\Block\Adminhtml
 */
class GridPageActions extends PageActions
{
    /**
     * @var string
     */
    protected $actionButton = './/button[@id="%s"]';

    /**
     * @param $button
     * @return mixed
     */
    public function actionButtonIsVisible($button)
    {
        return $this->_rootElement->find(sprintf($this->actionButton, $button), Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * @param $actionButton
     * @throws \Exception
     */
    public function clickActionButton($actionButton)
    {
        $button = $this->_rootElement->find(sprintf($this->actionButton, $actionButton), Locator::SELECTOR_XPATH);
        if (!$button->isVisible()) {
            throw new \Exception('Main menu item "' . $actionButton . '" is not visible.');
        }
        $button->click();
        $this->waitForElementNotVisible(sprintf($this->actionButton, $actionButton), Locator::SELECTOR_XPATH);
    }
}