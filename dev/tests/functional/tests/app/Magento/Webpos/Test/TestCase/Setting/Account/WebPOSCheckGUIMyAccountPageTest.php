<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 22/02/2018
 * Time: 10:48
 */

namespace Magento\Webpos\Test\TestCase\Setting\Account;

use Magento\Mtf\TestCase\Injectable;
use Magento\Webpos\Test\Page\WebposIndex;
use Magento\Mtf\Config\DataInterface;
/**
 * Class WebPOSCheckGUIMyAccountPageTest
 * @package Magento\Webpos\Test\TestCase\Setting\Account
 */
class WebPOSCheckGUIMyAccountPageTest  extends Injectable
{
    /**
     * @var WebposIndex
     */
    protected $webposIndex;

    /**
     * System config.
     *
     * @var DataInterface
     */
    protected $configuration;


    public function __inject(
        DataInterface $configuration,
        WebposIndex $webposIndex
    )
    {
        $this->configuration = $configuration;
        $this->webposIndex = $webposIndex;
    }

    public function test()
    {
        $username = $this->configuration->get('application/0/backendLogin/0/value');
        // Login webpos
        $staff = $this->objectManager->getInstance()->create(
            'Magento\Webpos\Test\TestStep\LoginWebposStep'
        )->run();

        $this->webposIndex->getCheckoutProductList()->waitProductListToLoad();
        $this->webposIndex->getMsWebpos()->waitCartLoader();

        $this->webposIndex->getMsWebpos()->clickCMenuButton();
        $this->webposIndex->getCMenu()->account();
        sleep(1);
        return [
            'username' => $username
        ];
    }
}