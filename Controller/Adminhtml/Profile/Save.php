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

use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterfaceFactory;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterfaceFactory;
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use ACedraz\CustomerImport\Logger\Logger;

/**
 * Class Save
 * @package ACedraz\CustomerImport\Controller\Adminhtml\Profile
 */
class Save extends Action
{
    /** @var string */
    const DATA_PERSISTOR_KEY = 'acedraz_customerimport_profile_entity';

    /** @var Logger */
    private Logger $logger;

    /** @var DataPersistorInterface */
    private DataPersistorInterface $dataPersistor;

    /** @var ProfileRepositoryInterface */
    private ProfileRepositoryInterface $profileRepository;

    /** @var ProfileInterfaceFactory */
    private ProfileInterfaceFactory $profileFactory;

    /** @var ProfileMapInterfaceFactory */
    private ProfileMapInterfaceFactory $profileMapFactory;

    /**
     * @param DataPersistorInterface $dataPersistor
     * @param ProfileRepositoryInterface $profileRepository
     * @param ProfileInterfaceFactory $profileFactory
     * @param ProfileMapInterfaceFactory $profileMapFactory
     * @param Logger $logger
     * @param Context $context
     */
    public function __construct(
        DataPersistorInterface $dataPersistor,
        ProfileRepositoryInterface $profileRepository,
        ProfileInterfaceFactory $profileFactory,
        ProfileMapInterfaceFactory $profileMapFactory,
        Logger $logger,
        Context $context
    ) {
        $this->logger = $logger;
        $this->dataPersistor = $dataPersistor;
        $this->profileRepository = $profileRepository;
        $this->profileFactory = $profileFactory;
        parent::__construct($context);
        $this->profileMapFactory = $profileMapFactory;
    }

    /**
     * @return ProfileMapInterface
     */
    private function getProfileMapEntity(): ProfileMapInterface
    {
        return $this->profileMapFactory->create();
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if ($data['entity_id']) {
                try {
                    $profile = $this->profileRepository->get($data['entity_id']);
                    if (!$profile->getId()) {
                        $this->messageManager->addErrorMessage(__('This profile no longer exists.'));
                        return $resultRedirect->setPath('*/*/');
                    }
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    $this->logger->error($e->getMessage());
                    return $resultRedirect->setPath('*/*/');
                }
            }
            if (!$data['entity_id']) {
                unset($data['entity_id']);
            }
            $profile = $this->profileFactory->create();
            $profile = $this->updateData($profile, $data);
            try {
                $profile = $this->profileRepository->save($profile);
                $this->messageManager->addSuccessMessage(__('You saved the profile.'));
                $this->dataPersistor->clear(self::DATA_PERSISTOR_KEY);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $profile->getEntityId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the request.'));
                $this->logger->error($e->getMessage());
            }
            $this->dataPersistor->set(self::DATA_PERSISTOR_KEY, $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function updateData(ProfileInterface $profile,array $data)
    {
        $profile->setData($data);
        if (!$profile->getData('maps_container')) {
            $profile->setMap([$this->getProfileMapEntity()]);
            return $profile;
        }
        $profileMap = [];
        foreach ($profile->getData('maps_container') as $mapContainer) {
            $profileMap[] = $this->getProfileMapEntity()
                ->setColumn($mapContainer['column'])
                ->setAttribute($mapContainer['attribute'])
                ->setProfileId($profile->getEntityId());
        }
        return $profile->setMap($profileMap);
    }
}
