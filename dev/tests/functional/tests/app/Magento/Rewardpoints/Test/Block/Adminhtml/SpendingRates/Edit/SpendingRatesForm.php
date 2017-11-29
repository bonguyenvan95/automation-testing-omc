<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 11/28/2017
 * Time: 8:26 AM
 */
namespace Magento\Rewardpoints\Test\Block\Adminhtml\SpendingRates\Edit;

use Magento\Backend\Test\Block\Widget\FormTabs;
use Magento\Mtf\Client\Locator;

class SpendingRatesForm extends FormTabs
{
    protected $formTitle = './/span[text()="Spending Rate Information"]';

    protected $spendingPointField = '[data-index="points"]';

    public function formTitleIsVisible()
    {
        return $this->_rootElement->find($this->formTitle, Locator::SELECTOR_XPATH)->isVisible();
    }

    public function spendingPointFieldIsVisible()
    {
        return $this->_rootElement->find($this->spendingPointField, Locator::SELECTOR_CSS)->isVisible();
    }
}