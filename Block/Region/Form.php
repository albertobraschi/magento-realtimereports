<?php
class Cammino_Realtimereports_Block_Region_Form extends Mage_Sales_Block_Adminhtml_Report_Filter_Form_Order
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
        $fieldset->removeField('period_type');

        $fieldset->addField('report_type', 'select', array(
            'name' => 'report_type',
            'options' => array(
                'region' => Mage::helper('reports')->__('By Region'),
                'city' => Mage::helper('reports')->__('By City')
            ),
            'label' => Mage::helper('reports')->__('Report Type'),
            'title' => Mage::helper('reports')->__('Report Type')
        ));

        $fieldset->addField('order_statuses', 'multiselect', array(
            'name'      => 'order_statuses',
            'values'    => array(
                array("label" => "Aprovados", "value" => "processing"),
                array("label" => "Cancelados", "value" => "canceled"),
                array("label" => "Pendentes", "value" => "pending"),
                array("label" => "Em Espera", "value" => "holded")
            ),
            'display'   => 'none'
        ), 'show_order_statuses');

        return $this;
            
    }
}