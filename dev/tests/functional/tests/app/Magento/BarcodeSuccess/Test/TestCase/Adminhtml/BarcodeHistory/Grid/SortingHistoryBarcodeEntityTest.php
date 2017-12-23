<?php
/**
 * Created by PhpStorm.
 * User: GVT
 * Date: 12/22/2017
 * Time: 1:45 PM
 */
namespace Magento\BarcodeSuccess\Test\TestCase\Adminhtml\BarcodeHistory\Grid;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\TestFramework\Inspection\Exception;
use Magento\Ui\Test\TestCase\GridSortingTest;
class SortingHistoryBarcodeEntityTest extends GridSortingTest
{

    protected $gridBlock;
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $product = $fixtureFactory->createByCode(
            'catalogProductSimple',
            [
                'dataset' => 'productForBarcodeHistory2',
            ]
        );
        $product->persist();
        $barcode = $fixtureFactory->createByCode(
            'barcode',
            [
                'dataset' => 'barcodeHistory2',
            ]
        );
        $barcode->persist();
        return ['barcode' => $barcode];
    }

    public function test(
        $pageClass,
        $gridRetriever,
        array $columnsForSorting,
        $fixtureName = null,
        $fixtureDataSet = null,
        $itemsCount = null,
        array $steps = []
    ) {
        $productdatasets = explode(',', $fixtureDataSet)[1];
        $fixtureDataSet = explode(',', $fixtureDataSet)[0];
        $productdatasets = explode(',', $productdatasets);
        foreach ($productdatasets as &$productdataset) {
            $product = $this->fixtureFactory->createByCode(
                'catalogProductSimple',
                [
                    'dataset' => $productdataset,
                ]
            );
            $product->persist();
        }
        // Fill grid before sorting if needed
        if ($fixtureName && $fixtureDataSet && $itemsCount && $steps) {
            $this->createItems($itemsCount, $fixtureName, $fixtureDataSet, $steps);
        }

        $page = $this->pageFactory->create($pageClass);

        // Steps
        $page->open();
        /** @var DataGrid $gridBlock */
        $gridBlock = $page->$gridRetriever();
        $this->gridBlock = $gridBlock;
        $gridBlock->resetFilter();

        $sortingResults = [];
        foreach ($columnsForSorting as $columnName) {
            $gridBlock->resetFilter();
            $gridBlock->sortByColumn($columnName);
            $gridBlock->waitingForLoadingMaskNotVisible();
            $sortingResults[$columnName]['firstIdAfterFirstSoring'] = $gridBlock->getItemId();
            $gridBlock->sortByColumn($columnName);
            $gridBlock->waitingForLoadingMaskNotVisible();
            $sortingResults[$columnName]['firstIdAfterSecondSoring'] = $gridBlock->getItemId();
        }
//        throw new Exception(var_dump($sortingResults));
        return ['sortingResults' => $sortingResults];
    }
}