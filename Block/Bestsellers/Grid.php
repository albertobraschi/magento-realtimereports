<?php
class Cammino_Realtimereports_Block_Bestsellers_Grid extends Mage_Adminhtml_Block_Report_Grid_Abstract
{
    protected $_columnGroupBy = 'period';

    public function __construct()
    {
        parent::__construct();
        $this->setCountTotals(true);
    }

    public function getResourceCollectionName()
    {
        return 'realtimereports/bestsellers_collection';
    }

    protected function _prepareColumns()
    {
        // $this->addColumn('period', array(
        //     'header'        => Mage::helper('sales')->__('Period'),
        //     'index'         => 'period',
        //     'width'         => 100,
        //     'sortable'      => false,
        //     'period_type'   => $this->getPeriodType(),
        //     'renderer'      => 'adminhtml/report_sales_grid_column_renderer_date',
        //     'totals_label'  => Mage::helper('sales')->__('Total'),
        //     'html_decorators' => array('nobr'),
        // ));

        $this->addColumn('product_name', array(
            'header'    => Mage::helper('sales')->__('Product Name'),
            'index'     => 'product_name',
            'type'      => 'text',
            'total'     => 'sum',
            'sortable'  => false,
            'totals_label'  => Mage::helper('sales')->__('Total'),
            'html_decorators' => array('nobr'),
        ));

        $this->addColumn('qty_ordered', array(
            'header'    => Mage::helper('sales')->__('Qty Ordered'),
            'index'     => 'qty_ordered',
            'type'      => 'number',
            'total'     => 'sum',
            'sortable'  => false
        ));

        if ($this->getFilterData()->getStoreIds()) {
            $this->setStoreIds(explode(',', $this->getFilterData()->getStoreIds()));
        }
        
        $currencyCode = $this->getCurrentCurrencyCode();

        // $this->addColumn('grand_total', array(
        //     'header'        => Mage::helper('sales')->__('Sales Total'),
        //     'type'          => 'currency',
        //     'currency_code' => $currencyCode,
        //     'index'         => 'grand_total',
        //     'total'         => 'sum',
        //     'sortable'      => false
        // ));

        $this->addExportType('*/*/exportSalesCsv', Mage::helper('adminhtml')->__('CSV'));
        $this->addExportType('*/*/exportSalesExcel', Mage::helper('adminhtml')->__('Excel XML'));

        return parent::_prepareColumns();
    }

}