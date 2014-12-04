<?php
class Cammino_Realtimereports_Model_Mysql4_Customers_Collection extends Mage_Sales_Model_Mysql4_Report_Collection_Abstract
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
        if (!$this->isTotals()) {

            $this->getSelect()->from('customer_entity', array(
                'customer_id' => 'sales_flat_order.customer_id',
                'grand_total' => 'SUM(sales_flat_order.grand_total)',
                'orders_count' => 'COUNT(sales_flat_order.entity_id)',
                'customer_name' => 'CONCAT(CONCAT(name_attribute.value, \' \'), lastname_attribute.value)',
            ));

            $this->getSelect()->join('sales_flat_order', 'sales_flat_order.customer_id = customer_entity.entity_id', array());
            $this->getSelect()->joinLeft(array('name_attribute' => 'customer_entity_varchar'), 'name_attribute.entity_id = customer_entity.entity_id', array());
            $this->getSelect()->joinLeft(array('lastname_attribute' => 'customer_entity_varchar'), 'lastname_attribute.entity_id = customer_entity.entity_id', array());
            $this->getSelect()->where('name_attribute.attribute_id = 1');
            $this->getSelect()->where('lastname_attribute.attribute_id = 2');
            $this->getSelect()->group('sales_flat_order.customer_id');
            $this->getSelect()->order(array('SUM(sales_flat_order.grand_total) DESC'));

        } else {

            $this->getSelect()->from('sales_flat_order', array(
                'grand_total' => 'SUM(sales_flat_order.grand_total)',
                'orders_count' => 'COUNT(sales_flat_order.entity_id)'
            ));
            $this->getSelect();
        }

        return $this;
    }

    protected function _applyDateRangeFilter()
    {
        $tz = Mage::app()->getLocale()->storeDate($store)->toString(Zend_Date::GMT_DIFF_SEP);
        
        if (!is_null($this->_from)) {
            $this->getSelect()->where(' DATE(CONVERT_TZ(sales_flat_order.created_at, \'+00:00\', \''.$tz.'\')) >= ?', $this->_from);
        }
        if (!is_null($this->_to)) {
            $this->getSelect()->where(' DATE(CONVERT_TZ(sales_flat_order.created_at, \'+00:00\', \''.$tz.'\')) <= ?', $this->_to);
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


