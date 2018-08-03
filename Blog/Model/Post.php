<?php
namespace ThangDz\Blog\Model;
class Post extends \Magento\Framework\Model\AbstractModel
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected function construct()
    {
        $this->_init('ThangDz\Blog\Model\ResourceModel\Post');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => _('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}