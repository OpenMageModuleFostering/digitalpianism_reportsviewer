<?php

/**
 * Class DigitalPianism_ReportsViewer_Adminhtml_ReportsviewerController
 */
class DigitalPianism_ReportsViewer_Adminhtml_ReportsviewerController extends Mage_Adminhtml_Controller_Action
{

	/**
	 * Check the ACL permission
	 * @return mixed
     */
	protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/tools/reportsviewer');
    }

	/**
	 * Initialization
	 * @return $this
	 */
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('system/tools/reportsviewer');

		return $this;
	}

	/**
	 * This is the action used to display the grid
     */
	public function indexAction()
	{
		try {
			$this->_initAction()->renderLayout();
		}
		catch(Exception $ex) {
			Mage::helper('reportsviewer')->log(sprintf("%s->error=%s", __METHOD__, print_r($ex, true)), Zend_Log::DEBUG );
		}
	}

	/**
	 * This is called when deleting an item from its edit page
     */
	public function deleteAction() {
		// We first retrieve the ID
		$reportId = (int) $this->getRequest()->getParam('id');
		// Set the location of the report
		$reportFolder = Mage::getBaseDir('var') . '/report';

		if ($reportId)
		{
			try {
				// We physically (so to say) delete the file
				unlink($reportFolder . "/" . $reportId);
				// Success message
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reportsviewer')->__('Report was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/view', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		// Redirection to the grid
		$this->_redirect('*/*/');
	}

	/**
	 * This is called when we mass delete items from the grid
     */
	public function massDeleteAction()
	{
		// We get the IDs of the items that need to be deleted
		$reportIds = $this->getRequest()->getParam('reportsviewer');
		// Set the location of the reports
		$reportFolder = Mage::getBaseDir('var') . '/report';

		if (!is_array($reportIds))
		{
			// Display an error if the parameter is not an array
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reportsviewer')->__('Please select report(s)'));
		}
		else
		{
			try {
				// Loop through the reports IDs
				foreach($reportIds as $reportId)
				{
					// Delete them manually
					unlink($reportFolder . "/" . $reportId);
				}
				// Success message
				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('reportsviewer')->__(
						'Total of %d report(s) were successfully deleted', count($reportIds)
					)
				);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		// Redirection to the grid
		$this->_redirect('*/*/index');
	}

	/**
	 * This is the action used to view the details of a report
     */
	public function viewAction()
	{
		// We first retrieve the report ID
		$id = $this->getRequest()->getParam('id');

		// This is the location of the reports
		$reportFolder = Mage::getBaseDir('var') . '/report';

		// Then we create a new Object
		$model = new Varien_Object();
		// Set some data
		$model->setReportId($id);
		$model->setFile($reportFolder . "/" . $id);
		$model->setAdded(filemtime($reportFolder . "/" . $id));

		// Retrieve the content from the file
		$content = file_get_contents($reportFolder . "/" . $id);
		// Decode it
		$content = unserialize($content);
		// Loop through the content array
		foreach ($content as $key => $value)
		{
			// The value with the key = 0 of the array is always the error message
			if (!$key)
			{
				$model->setError($value);
			}
			elseif ($key == "url")
			{
				$model->setUrl($value);
			}
			elseif ($key == "script_name")
			{
				$model->setScriptName($value);
			}
			elseif ($key == "skin")
			{
				$model->setSkin($value);
			}
			else
			{
				// The trace has the key = 1, we do it last
				$model->setTrace($value);
			}
		}

		// Register the data so we can use it in the form
		Mage::register('report_data', $model);

		// Layout loading / rendering
		$this->loadLayout();
		$this->_setActiveMenu('system/tools/reportsviewer');

		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

		$this->_addContent($this->getLayout()->createBlock('reportsviewer/adminhtml_reportsviewer_view'));

		$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);


		$this->renderLayout();
	}

}