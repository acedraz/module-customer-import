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
use ACedraz\CustomerImport\Logger\Logger;
use ACedraz\CustomerImport\Model\ResourceModel\Profile\CollectionFactory as Collection;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package ACedraz\CustomerImport\Controller\Adminhtml\Profile
 */
class MassDelete extends Action
{
    /**
     * Mass action Filter
     *
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected Filter $_filter;

    /** @var ProfileRepositoryInterface */
    private ProfileRepositoryInterface $profileRepository;

    /** @var Collection */
    private Collection $collectionFactory;

    /** @var Logger */
    private Logger $logger;

    /**
     * constructor
     * @param Logger $logger
     * @param Collection $collection
     * @param ProfileRepositoryInterface $profileRepository
     * @param Filter $filter
     * @param Context $context
     */
    public function __construct(
        Logger $logger,
        Collection $collection,
        ProfileRepositoryInterface $profileRepository,
        Filter  $filter,
        Context $context
    ) {
        $this->profileRepository = $profileRepository;
        $this->_filter = $filter;
        $this->collectionFactory = $collection;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * execute action
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $collection = $this->_filter->getCollection($this->collectionFactory->create());
            if ($collection->getSize()) {
                /** @var ProfileInterfaceFactory $item */
                foreach ($collection->getItems() as $item) {
                    $this->profileRepository->deleteById($item->getId());
                }
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collection->getSize()));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->logger->error($e->getMessage());
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
