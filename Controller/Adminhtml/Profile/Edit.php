<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.2
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Controller\Adminhtml\Profile;

use ACedraz\CustomerImport\Api\Data\ProfileInterfaceFactory;
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\Page;

/**
 * Class Edit
 * @package ACedraz\CustomerImport\Controller\Adminhtml\Profile
 */
class Edit extends AbstractProfile
{
    /** @var ProfileRepositoryInterface */
    private ProfileRepositoryInterface $profileRepository;

    /** @var ProfileInterfaceFactory */
    private ProfileInterfaceFactory $profileFactory;

    /**
     * @param ProfileInterfaceFactory $profileFactory
     * @param ProfileRepositoryInterface $profileRepository
     * @param Context $context
     */
    public function __construct(
        ProfileInterfaceFactory $profileFactory,
        ProfileRepositoryInterface $profileRepository,
        Context $context
    ) {
        $this->profileRepository = $profileRepository;
        $this->profileFactory = $profileFactory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     * Note: Request will be added as operation argument in future
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        try {
            $profile = $this->profileFactory->create();
            if ($profile = $this->profileRepository->get((int)$this->getRequest()->getParam('id'))) {
                if (!$profile->getId()) {
                    $this->messageManager->addErrorMessage(__('This profile no longer exists.'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            }
        } catch (LocalizedException $e) {}
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage)->addBreadcrumb(
            $profile->getId() ? __('Edit Profile') : __('New Profile'),
            $profile->getId() ? __('Edit Profile') : __('New Profile')
        );
        $resultPage->getConfig()->getTitle()->set(
            $profile->getId() ? __('Edit Profile') : __('Create Profile')
        );
        return $resultPage;
    }
}
