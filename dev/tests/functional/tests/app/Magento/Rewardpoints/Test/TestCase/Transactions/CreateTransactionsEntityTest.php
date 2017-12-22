<?php
/**
 * Created by PhpStorm.
 * User: PhucDo
 * Date: 12/14/2017
 * Time: 1:31 PM
 */

namespace Magento\Rewardpoints\Test\TestCase\Transactions;

use Magento\Mtf\TestCase\Injectable;
use Magento\Rewardpoints\Test\Page\Adminhtml\TransactionIndex;
use Magento\Rewardpoints\Test\Page\Adminhtml\TransactionNew;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Rewardpoints\Test\Fixture\Transaction;

/**
 * Class CreateTransactionsEntityTest
 * @package Magento\Rewardpoints\Test\TestCase\Transactions
 */
class CreateTransactionsEntityTest extends Injectable
{

    /**
     * @var TransactionIndex
     */
    protected $transactionIndex;

    /**
     * @var TransactionNew
     */
    protected $transactionNew;

    /**
     * @param TransactionIndex $transactionIndex
     * @param TransactionNew $transactionNew
     */
    public function __inject(TransactionIndex $transactionIndex, TransactionNew $transactionNew)
    {
        $this->transactionIndex = $transactionIndex;
        $this->transactionNew = $transactionNew;
    }

    public function test($button, Customer $customer, Transaction $transaction)
    {
//        $rate->persist();
        $customer->persist();

        $this->transactionIndex->open();
        $this->transactionIndex->getTransactionGridPageActions()->clickActionButton($button);
        $this->transactionNew->getTransactionForm()->clickSelectCustomer();
        $this->transactionNew->getSelectCustomerForm()->selectedCustomer($customer);
        $this->transactionNew->getTransactionForm()->fill($transaction);
        $this->transactionNew->getTransactionFormPageActions()->save();
    }
}