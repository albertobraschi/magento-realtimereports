<?php
class Cammino_Realtimereports_Block_Customers_Grid extends Mage_Adminhtml_Block_Report_Grid_Abstract
{
    protected $_columnGroupBy = 'period';

    public function __construct()
    {
        parent::__construct();
        $this->setCountTotals(true);
    }

    public function getResourceCollectionName()
    {
        return ($this->getFilterData()->getData('report_type') == 'total')
            ? 'realtimereports/customers_collection'
            : 'realtimereports/customers_qtyordered_collection';
    }

    protected function _prepareColumns()
    {
        $this->addColumn('customer_name', array(
            'header'    => Mage::helper('sales')->__('Customer'),
            'index'     => 'customer_name',
            'type'      => 'text',
            'total'     => 'sum',
            'sortable'  => false,
            'totals_label'  => Mage::helper('sales')->__('Total'),
            'html_decorators' => array('nobr'),
        ));

        $this->addColumn('orders_count', array(
            'header'    => Mage::helper('sales')->__('Orders'),
            'index'     => 'orders_count',
            'type'      => 'number',
            'total'     => 'sum',
            'sortable'  => false
        ));

        if ($this->getFilterData()->getStoreIds()) {
            $this->setStoreIds(explode(',', $this->getFilterData()->getStoreIds()));
        }
        
        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn('grand_total', array(
            'header'        => Mage::helper('sales')->__('Sales Total'),
            'type'          => 'currency',
            'currency_code' => $currencyCode,
            'index'         => 'grand_total',
            'total'         => 'sum',
            'sortable'      => false
        ));

        $this->addExportType('*/*/exportSalesCsv', Mage::helper('adminhtml')->__('CSV'));
        $this->addExportType('*/*/exportSalesExcel', Mage::helper('adminhtml')->__('Excel XML'));

        return parent::_prepareColumns();
    }

}