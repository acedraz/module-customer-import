<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.6
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Controller\Adminhtml\Profile;

use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use ACedraz\CustomerImport\Logger\Logger;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 * @package ACedraz\CustomerImport\Controller\Adminhtml\Profile
 */
class Delete extends AbstractProfile
{
    /** @var ProfileRepositoryInterface */
    private ProfileRepositoryInterface $repository;

    /** @var Logger */
    private Logger $logger;

    /**
     * @param Logger $logger
     * @param Context $context
     * @param ProfileRepositoryInterface $repository
     */
    public function __construct(
        Logger $logger,
        Context $context,
        ProfileRepositoryInterface $repository
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $this->repository->deleteById((int) $this->getRequest()->getParam('id'));
            $this->messageManager->addSuccessMessage(
                __('A total of 1 record(s) have been deleted.')
            );
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getLogMessage());
            $this->messageManager->addErrorMessage(
                __(
                    'A total of 1 record(s) haven\'t been deleted. Please see server logs for more details.'
                )
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}
