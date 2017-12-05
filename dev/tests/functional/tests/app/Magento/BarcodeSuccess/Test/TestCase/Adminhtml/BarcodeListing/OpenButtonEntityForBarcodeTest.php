<?php

namespace Magento\BarcodeSuccess\Test\TestCase\Adminhtml\BarcodeListing;
use Magento\Mtf\TestCase\Injectable;
use Magento\BarcodeSuccess\Test\Page\Adminhtml\BarcodeListing\BarcodeIndex;

class OpenButtonEntityForBarcodeTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * @var BarcodeIndex $barcodeIndex
     */
    protected $barcodeIndex;

    public function __inject(
        BarcodeIndex $barcodeIndex
    ) {
        $this->barcodeIndex = $barcodeIndex;
    }
    public function test($id)
    {
        $id = '#'.$id;
        $this->barcodeIndex->open();
        $this->barcodeIndex->getBarcodeGrid()->waitingForLoadingMaskNotVisible();
        $this->barcodeIndex->getManageBarcodes()->clickButton($id);
        if($id = '#generate')
            $this->barcodeIndex->getBarcodeGrid()->waitingForLoadingMaskNotVisible();
        else
            sleep(1);
    }
}