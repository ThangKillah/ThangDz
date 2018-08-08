<?php
/**
 * Created by PhpStorm.
 * User: thangbt
 * Date: 8/8/2018
 * Time: 5:32 PM
 */

namespace ThangDz\Blog\Model\Post\Source;
use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{

    protected $cmsPage;
    public function __construct(\ThangDz\Blog\Model\Post $cmsPage)
    {
        $this->cmsPage = $cmsPage;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->cmsPage->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
