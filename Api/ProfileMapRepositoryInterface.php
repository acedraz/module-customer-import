<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.1
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Api;

use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface ProfileMapRepositoryInterface
 * @package ACedraz\CustomerImport\Api
 */
interface ProfileMapRepositoryInterface
{
    /**
     * @param ProfileMapInterface $profile
     * @return ProfileMapInterface
     * @throws CouldNotSaveException
     */
    public function save(ProfileMapInterface $profile): ProfileMapInterface;

    /**
     * @param $profileMapId
     * @return ProfileMapInterface
     * @throws NoSuchEntityException
     */
    public function get($profileMapId);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param ProfileMapInterface $profile
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ProfileMapInterface $profile): bool;

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($profileId): bool;
}
