<?php
namespace ThangDz\Blog\Controller\Adminhtml\Post;

use Magento\Cms\Model\Page\DomValidationState;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Config\Dom\ValidationException;
use Magento\Framework\Config\Dom\ValidationSchemaException;

class PostDataProcessor
{
    protected $dateFilter;
    protected $validatorFactory;
    protected $messageManager;
    private $validationState;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\View\Model\Layout\Update\ValidatorFactory $validatorFactory,
        DomValidationState $validationState = null
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
        $this->validatorFactory = $validatorFactory;
        $this->validationState = $validationState
            ?: ObjectManager::getInstance()->get(DomValidationState::class);
    }

    public function filter($data)
    {
        $filterRules = [];

        foreach (['custom_theme_from', 'custom_theme_to'] as $dateField) {
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->dateFilter;
            }
        }

        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }

    // validate post data
    public function validate($data)
    {
        if (!empty($data['layout_update_xml']) || !empty($data['custom_layout_update_xml'])) {
            /** @var $layoutXmlValidator \Magento\Framework\View\Model\Layout\Update\Validator */
            $layoutXmlValidator = $this->validatorFactory->create(
                [
                    'validationState' => $this->validationState,
                ]
            );

            if (!$this->validateData($data, $layoutXmlValidator)) {
                $validatorMessages = $layoutXmlValidator->getMessages();
                foreach ($validatorMessages as $message) {
                    $this->messageManager->addErrorMessage($message);
                }
                return false;
            }
        }
        return true;
    }

    // check require field
    public function validateRequireEntry(array $data)
    {
        $requiredFields = [
            'title' => __('Post Title'),
            'status' => __('Status')
        ];
        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addError(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }

    private function validateData($data, $layoutXmlValidator)
    {
        try {
            if (!empty($data['layout_update_xml']) && !$layoutXmlValidator->isValid($data['layout_update_xml'])) {
                return false;
            }
            if (!empty($data['custom_layout_update_xml']) &&
                !$layoutXmlValidator->isValid($data['custom_layout_update_xml'])
            ) {
                return false;
            }
        } catch (ValidationException $e) {
            return false;
        } catch (ValidationSchemaException $e) {
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e);
            return false;
        }

        return true;
    }
}
