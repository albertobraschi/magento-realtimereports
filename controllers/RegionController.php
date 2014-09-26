<?php
class Cammino_Realtimereports_RegionController extends Mage_Adminhtml_Controller_Action
{

	protected $_adminSession = null;

	public function _initAction()
	{
		$this->loadLayout()
			->_addBreadcrumb(Mage::helper('reports')->__('Reports'), Mage::helper('reports')->__('Reports'))
			->_addBreadcrumb(Mage::helper('reports')->__('Sales'), Mage::helper('reports')->__('Sales'));
		return $this;
	}

	public function _initReportAction($blocks)
	{
		if (!is_array($blocks)) {
			$blocks = array($blocks);
		}

		$requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
		$requestData = $this->_filterDates($requestData, array('from', 'to'));
		$requestData['store_ids'] = $this->getRequest()->getParam('store_ids');
		$params = new Varien_Object();

		foreach ($requestData as $key => $value) {
			if (!empty($value)) {
				$params->setData($key, $value);
			}
		}

		foreach ($blocks as $block) {
			if ($block) {
				$block->setRegionType($params->getData('region_type'));
				$block->setFilterData($params);
			}
		}

		return $this;
	}

	protected function _getCollectionNames()
	{
		return array();
	}

	protected function _getSession()
	{
		if (is_null($this->_adminSession)) {
			$this->_adminSession = Mage::getSingleton('admin/session');
		}
		return $this->_adminSession;
	}

	public function salesAction()
	{
		$this->_title($this->__('Reports'))->_title($this->__('Sales'))->_title($this->__('Sales'));

		$this->_initAction()
			->_setActiveMenu('report/sales/sales')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Sales Report'), Mage::helper('adminhtml')->__('Sales Report'));

		$gridBlock = $this->getLayout()->getBlock('report_sales_sales.grid');
		$filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');

		$this->_initReportAction(array(
			$gridBlock,
			$filterFormBlock
		));

		$this->renderLayout();
	}

	public function exportSalesCsvAction()
	{
		$fileName = 'sales.csv';
		$grid = $this->getLayout()->createBlock('realtimereports/region_grid');
		$this->_initReportAction($grid);
		$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
	}

	public function exportSalesExcelAction()
	{
		$fileName = 'sales.xml';
		$grid = $this->getLayout()->createBlock('realtimereports/region_grid');
		$this->_initReportAction($grid);
		$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
	}

}
?>