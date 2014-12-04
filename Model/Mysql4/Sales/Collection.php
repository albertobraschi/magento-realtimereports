<?php
class Cammino_Realtimereports_Model_Mysql4_Sales_Collection extends Mage_Sales_Model_Mysql4_Report_Collection_Abstract
{
    protected $_periodFormat;
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
        if ('month' == $this->_period) {
            $this->_periodFormat = 'DATE_FORMAT(created_at, \'%Y-%m\')';
        } elseif ('year' == $this->_period) {
            $this->_periodFormat = 'EXTRACT(YEAR FROM created_at)';
        } else {
            $this->_periodFormat = 'DATE(created_at)';
        }

        if (!$this->isTotals()) {

            $this->getSelect()->from('sales_flat_order', array(
                'period' => $this->_periodFormat,
                'orders_count' => 'COUNT(entity_id)',
                'grand_total' => 'SUM(grand_total)'
            ));
            $this->getSelect()->group($this->_periodFormat);

        } else {

            $this->getSelect()->from('sales_flat_order', array(
                'orders_count' => 'COUNT(entity_id)',
                'grand_total' => 'SUM(grand_total)'
            ));
            $this->getSelect();

        }
        
        // $this->printLogQuery(true,true);

        return $this;
    }

    protected function _applyDateRangeFilter()
    {
        if (!is_null($this->_from)) {
            $this->getSelect()->where(' date(created_at) >= ?', $this->_from);
        }
        if (!is_null($this->_to)) {
            $this->getSelect()->where(' date(created_at) <= ?', $this->_to);
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


