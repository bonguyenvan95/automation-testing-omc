<?php
/**
 * Created by PhpStorm.
 * User: vinh
 * Date: 24/11/2017
 * Time: 10:15
 */

namespace Magento\InventorySuccess\Test\Block\Adminhtml;


use Magento\Ui\Test\Block\Adminhtml\DataGrid;

class Grid extends DataGrid
{
	protected $spinner = '[data-role="spinner"]';

	/**
	 * Wait page to load.
	 *
	 * @return void
	 */
	public function waitPageToLoad()
	{
		$this->waitForElementNotVisible($this->spinner);
	}

	public function TableIsVisible()
	{
		return $this->_rootElement->find('#container > div > div.admin__data-grid-wrap > table')->isVisible();
	}
}