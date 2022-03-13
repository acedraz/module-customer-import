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

use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface ProfileRepositoryInterface
 * @package ACedraz\CustomerImport\Api
 */
interface ProfileRepositoryInterface
{
    /**
     * @param ProfileInterface $profile
     * @return ProfileInterface
     * @throws CouldNotSaveException
     */
    public function save(ProfileInterface $profile): ProfileInterface;

    /**
     * @param $profileId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function get($profileId);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \ACedraz\CustomerImport\Api\Data\ProfileSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param ProfileInterface $profile
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ProfileInterface $profile): bool;

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($profileId): bool;
}
