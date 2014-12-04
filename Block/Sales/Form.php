<?php
class Cammino_Realtimereports_Block_Sales_Form extends Mage_Sales_Block_Adminhtml_Report_Filter_Form_Order
{
	protected function _prepareForm()
    {
    	parent::_prepareForm();
    	$form = $this->getForm();
    	$fieldset = $this->getForm()->getElement('base_fieldset');

        $fieldset->removeField('show_empty_rows');
        $fieldset->removeField('show_actual_columns');
        // $fieldset->removeField('show_order_statuses');
        $fieldset->removeField('report_type');
        $fieldset->removeField('order_statuses');


        $fieldset->addField('order_statuses', 'multiselect', array(
            'name' => 'order_statuses',
            'values' => $this->getStatuses(),
            'display' => 'none'
        ), 'show_order_statuses');

        return $this;
            
    }

    private function getStatuses() {
        $statuses = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
        $values = array();

        foreach ($statuses as $item) {
            $values[] = array("label" => $item["label"], "value" => $item["status"]);
        }

        return $values;
    }
}