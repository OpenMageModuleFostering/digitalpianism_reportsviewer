<?php

/**
 * Class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer_Grid
 * This is the block representing the grid of reports
 */
class DigitalPianism_Reportsviewer_Block_Adminhtml_Reportsviewer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     *	Constructor the grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('reportsviewerGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     *	Prepare the collection to display in the grid
     */
    protected function _prepareCollection()
    {
        // Create a collection
        $collection = new Varien_Data_Collection();

        // Add the reports from the var folder to the collection
        $reportFolder = Mage::getBaseDir('var') . '/report';

        // If the report folder is a directory
        if (is_dir($reportFolder))
        {
            // And if we can read it
            if ($dh = opendir($reportFolder))
            {
                // We loop through its readable files
                while (($file = readdir($dh)) !== false)
                {
                    // Except "." and ".."
                    if ($file != "." && $file != "..")
                    {
                        // For each file we create a new object
                        $newItem = new Varien_Object();
                        // Set some data
                        $newItem->setReportId($file);
                        $newItem->setFile($reportFolder . "/" . $file);
                        // Set the date properly
                        $dateAdded = Mage::getModel('core/date')->date(null,filemtime($reportFolder . "/" . $file));
                        $newItem->setAdded($dateAdded);

                        // Get the content of the file
                        $content = file_get_contents($reportFolder . "/" . $file);
                        // Decode it
                        $content = unserialize($content);
                        // Loop through the array
                        foreach ($content as $key => $value)
                        {
                            // Value with key = 0 is always the error message
                            if (!$key)
                            {
                                $newItem->setError($value);
                            }
                            elseif ($key == "url")
                            {
                                $newItem->setUrl($value);
                            }
                            elseif ($key == "script_name")
                            {
                                $newItem->setScriptName($value);
                            }
                            elseif ($key == "skin")
                            {
                                $newItem->setSkin($value);
                            }
                        }
                        // Once the data are set, we add the object to the collection
                        $collection->addItem($newItem);
                    }
                }
                // We close the folder
                closedir($dh);
            }
        }

        // We set the collection of the grid
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     *	Prepare the columns of the grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('report_id', array(
            'header' => Mage::helper('reportsviewer')->__('Report #'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'report_id',
        ));

        $this->addColumn('error', array(
            'header' => Mage::helper('reportsviewer')->__('Error'),
            'align' => 'right',
            'index' => 'error',
        ));

        $this->addColumn('url', array(
            'header' => Mage::helper('reportsviewer')->__('URL'),
            'align' => 'right',
            'index' => 'url',
        ));

        $this->addColumn('script_name', array(
            'header' => Mage::helper('reportsviewer')->__('Script Name'),
            'align' => 'right',
            'index' => 'script_name',
        ));

        $this->addColumn('skin', array(
            'header' => Mage::helper('reportsviewer')->__('Skin'),
            'align' => 'right',
            'index' => 'skin',
        ));

        $this->addColumn('file', array(
            'header' => Mage::helper('reportsviewer')->__('File'),
            'align' => 'right',
            'index' => 'file',
        ));

        $this->addColumn('added', array(
            'header' => Mage::helper('reportsviewer')->__('Created At'),
            'index' => 'added',
            'width' => '140px',
            'type' => 'datetime',
            'gmtoffset' => true,
            'default' => ' -- '
        ));

        // Here we use a custom renderer to be able to display what we want
        $this->addColumn('action', array(
            'header' => Mage::helper('reportsviewer')->__('Action'),
            'index' => 'stores',
            'sortable' => false,
            'filter' => false,
            'width' => '160',
            'is_system' => true,
            'renderer'  => 'DigitalPianism_ReportsViewer_Block_Adminhtml_Template_Grid_Renderer_Action'
        ));

        return parent::_prepareColumns();
    }

    /**
     *	Prepare mass actions
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('report_id');
        $this->getMassactionBlock()->setFormFieldName('reportsviewer');

        // Delete action
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('reportsviewer')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('reportsviewer')->__('Are you sure?')
        ));

        return $this;
    }

    /**
     *  Getter for the row URL
     *  @param $row
     *  @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getData('report_id')));
    }

}
