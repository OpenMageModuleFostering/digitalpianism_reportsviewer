<?php

/**
 * Class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer
 */
class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer extends Mage_Adminhtml_Block_Template
{
    /**
     * Block's template
     *
     * @var string
     */
    protected $_template = 'digitalpianism/reportsviewer/list.phtml';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->setChild('reportsviewerGrid',
            $this->getLayout()->createBlock('reportsviewer/adminhtml_reportsviewer_grid')
        );
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('reportsviewerGrid');
    }

}