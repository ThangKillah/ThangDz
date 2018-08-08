<?php
namespace ThangDz\Blog\Controller\Adminhtml\Post;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use ThangDz\Blog\Model\PostFactory;

class InlineEdit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'ThangDz_Blog::save';
    protected $postFactory;
    protected $jsonFactory;
    public function __construct(
        Context $context,
        PostFactory $postFactory,
        JsonFactory $jsonFactory
    )
    {
        parent::__construct($context);
        $this->postFactory = $postFactory;
        $this->jsonFactory = $jsonFactory;
    }
    public function execute()
    {
        // Init result Json
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        // Get POST data
        $postItems = $this->getRequest()->getParam('items', []);
        // Check request
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        // Save data to database
        foreach (array_keys($postItems) as $postId) {
            try {
                $post = $this->postFactory->create();
                $post->load($postId);
                $post->setData($postItems[(string)$postId]);
                $post->save();
            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving the post.');
                $error = true;
            }
        }
        // Return result Json
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}