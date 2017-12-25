<?php
/**
 * Created by PhpStorm.
 * User: gvt
 * Date: 23/12/2017
 * Time: 13:52
 */
namespace Magento\BarcodeSuccess\Test\TestCase\Adminhtml\BarcodeListing\Grid;
use Magento\Ui\Test\TestCase\GridFilteringTest;
class FilterBarcodeGenerateEntityTest extends GridFilteringTest{
    public function test(
        $pageClass,
        $gridRetriever,
        $idGetter,
        array $filters,
        $fixtureName,
        $itemsCount,
        array $steps = [],
        $fixtureDataSet = null,
        $idColumn = null
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


        $items = $this->createItems($itemsCount, $fixtureName, $fixtureDataSet, $steps);
        $page = $this->pageFactory->create($pageClass);

        // Steps
        $page->open();
        /** @var DataGrid $gridBlock */
        $gridBlock = $page->$gridRetriever();
        $gridBlock->resetFilter();

        $filterResults = [];
        foreach ($filters as $index => $itemFilters) {
            foreach ($itemFilters as $itemFiltersName => $itemFilterValue) {
                if (substr($itemFilterValue, 0, 1) === ':') {
                    $value = $items[$index]->getData(substr($itemFilterValue, 1));
                } else {
                    $value = $itemFilterValue;
                }
                $gridBlock->search([$itemFiltersName => $value]);
                $idsInGrid = $gridBlock->getAllIds();
                if ($idColumn) {
                    $filteredTargetIds = [];
                    foreach ($idsInGrid as $filteredId) {
                        $filteredTargetIds[] = $gridBlock->getColumnValue($filteredId, $idColumn);
                    }
                    $idsInGrid = $filteredTargetIds;
                }
                $filteredIds = $this->getActualIds($idsInGrid, $items, $idGetter);
                $filterResults[$items[$index]->$idGetter()][$itemFiltersName] = $filteredIds;
            }
        }

        return ['filterResults' => $filterResults];
    }
}