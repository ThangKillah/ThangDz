<?php
namespace ThangDz\Blog\Model\ResourceModel\Post;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'post_id';
    protected $_previewFlag;

    protected function _construct()
    {
        $this->_init(ThangDz\Blog\Model\Post::class, ThangDz\Blog\Model\ResourceModel\Post::class);
    }

    protected function _afterLoad()  {
        $this->performAfterLoadBlog('blog_post_store', 'post_id');
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    protected function _renderFiltersBefore() {
        $this->joinStoreRelationTable('blog_post_store', 'post_id');
    }
}
