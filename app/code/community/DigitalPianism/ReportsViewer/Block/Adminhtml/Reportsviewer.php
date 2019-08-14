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
        $this->setTemplate('digitalpianism/reportsviewer/list.phtml');
    }

    /**
     * Prepare the layout
     */
    protected function _prepareLayout()
    {
        // Add the grid
        $this->setChild('grid', $this->getLayout()->createBlock('reportsviewer/adminhtml_reportsviewer_grid', 'reportsviewer.grid'));
        return parent::_prepareLayout();
    }

    /**
     * Getter for the grid HTML
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

}