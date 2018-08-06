<?php
namespace ThangDz\Blog\Model\ResourceModel;
class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        // Table name + primary key column
        $this->_init('cowell_blog_post', 'post_id');
    }

}