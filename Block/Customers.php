<?php
class Cammino_Realtimereports_Block_Customers extends Mage_Adminhtml_Block_Report_Sales_Sales
{

    public function __construct()
    {
        parent::__construct();
        $this->_headerText = Mage::helper('reports')->__('Customers');
    }

    protected function _prepareLayout()
    {
    	parent::_prepareLayout();

        $this->setChild('grid',
            $this->getLayout()->createBlock('realtimereports/customers_grid',
            $this->_controller . '.grid')->setSaveParametersInSession(true) );
        
        return $this;
    }

    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/sales', array('_current' => true));
    }
}