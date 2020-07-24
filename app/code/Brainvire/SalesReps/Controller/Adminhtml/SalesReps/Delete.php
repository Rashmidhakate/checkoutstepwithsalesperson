<?php


namespace Brainvire\SalesReps\Controller\Adminhtml\SalesReps;

class Delete extends \Brainvire\SalesReps\Controller\Adminhtml\SalesReps
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('salesreps_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Brainvire\SalesReps\Model\SalesReps::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Salesreps.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['salesreps_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Salesreps to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
