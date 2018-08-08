<?php
namespace ThangDz\Blog\Block\Adminhtml\Post\Edit;

class GenericButton
{
    protected $urlBuilder;
    protected $registry;
    
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }
    
    public function getId()
    {
        $post = $this->registry->registry('blog_post');
        return $post ? $post->getPostId() : null;
    }
    
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}