<?php
namespace ThangDz\Blog\Controller\Adminhtml\Post;
use Magento\Backend\App\Action;
use ThangDz\Blog\Model\Post;
use Magento\Framework\App\Request\DataPersistorInterface;
class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'ThangDz_Blog::save';
    protected $dataProcessor;
    protected $dataPersistor;

    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor
    )
    {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            // Optimize data
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Post::STATUS_ENABLED;
            }
            if (empty($data['post_id'])) {
                $data['post_id'] = null;
            }

            // Init model and load by ID if exists
            $model = $this->_objectManager->create('ThangDz\Blog\Model\Post');
            $id = $this->getRequest()->getParam('post_id');
            if ($id) {
                $model->load($id);
            }
            // Validate data
            if (!$this->dataProcessor->validateRequireEntry($data)) {
                // Redirect to Edit page if has error
                return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getPostId(), '_current' => true]);
            }
            // Update model
            $model->setData($data);
            // Save data to database
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the Post.'));
                $this->dataPersistor->clear('blog_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getPostId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the post.'));
            }
            $this->dataPersistor->set('post', $data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        // Redirect to List page
        return $resultRedirect->setPath('*/*/');
    }
}