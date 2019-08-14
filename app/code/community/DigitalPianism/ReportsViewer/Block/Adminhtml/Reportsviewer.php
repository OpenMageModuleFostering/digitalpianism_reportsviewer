<?php

/**
 * Class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer
 */
class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_reportsviewer';
        $this->_blockGroup = 'reportsviewer';
        $this->_headerText = Mage::helper('reportsviewer')->__('Reports Viewer');
        parent::__construct();
        $this->_removeButton('add');
    }

}