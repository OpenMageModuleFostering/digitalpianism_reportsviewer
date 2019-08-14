<?php

/**
 * Class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer_View
 * This is the view page parent block
 */
class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     *	Constructor for the Edit page
     */
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'reportsviewer';
        $this->_controller = 'adminhtml_reportsviewer';
        $this->_mode = 'view';
        $this->_updateButton('delete', 'label', Mage::helper('reportsviewer')->__('Delete Report File'));
        // Remove the save button as we do not deal with editable data
        $this->_removeButton('save');
    }

    /**
     * @return mixed
     */
    protected function _prepareLayout()
    {
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            // Here we set the form block
            $this->setChild('form', $this->getLayout()->createBlock('reportsviewer/adminhtml_reportsviewer_view_form'));
        }
        return parent::_prepareLayout();
    }

    /**
     *	Getter for the header text
     */
    public function getHeaderText()
    {
        return Mage::helper('reportsviewer')->__('Viewing Report');
    }

}