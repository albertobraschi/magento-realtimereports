<?php
class Cammino_Realtimereports_Model_Mysql4_Bestsellers_Collection extends Mage_Sales_Model_Mysql4_Report_Collection_Abstract
{
    protected $_selectedColumns = array();

    public function __construct()
    {
        parent::_construct();
        $this->setModel('adminhtml/report_item');
        $this->_resource = Mage::getResourceModel('sales/report')->init('sales/order_aggregated_created');
        $this->setConnection($this->getResource()->getReadConnection());
    }

    protected function _initSelect()
    {
        if (!$this->isTotals()) {

            $this->getSelect()->from('sales_flat_order_item', array(
                'product_name' => 'sales_flat_order_item.name',
                'qty_ordered' => 'SUM(sales_flat_order_item.qty_ordered)'
            ));

            $this->getSelect()->join('sales_flat_order', 'sales_flat_order_item.order_id = sales_flat_order.entity_id');

            $this->getSelect()->group('product_id');

        } else {

            $this->getSelect()->from('sales_flat_order_item', array(
                'product_id' => 'product_id',
                'qty_ordered' => 'SUM(qty_ordered)'
            ));

            $this->getSelect()->join('sales_flat_order', 'sales_flat_order_item.order_id = sales_flat_order.entity_id');
        }

        $this->getSelect()->order(array('SUM(sales_flat_order_item.qty_ordered) DESC'));
        
        // $this->printLogQuery(true,true);

        return $this;
    }

    protected function _applyDateRangeFilter()
    {
        if (!is_null($this->_from)) {
            $this->getSelect()->where('sales_flat_order.created_at >= ?', $this->_from);
        }
        if (!is_null($this->_to)) {
            $this->getSelect()->where('sales_flat_order.created_at <= ?', $this->_to);
        }
        return $this;
    }

    protected function _applyStoresFilter()
    {
        return $this;
    }    

    protected function _applyOrderStatusFilter()
    {
        if (is_null($this->_orderStatus)) {
            return $this;
        }
        $orderStatus = $this->_orderStatus;
        if (!is_array($orderStatus)) {
            $orderStatus = array($orderStatus);
        }
        $this->getSelect()->where('status IN(?)', $orderStatus);
        return $this;
    }
}


