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

namespace ACedraz\CustomerImport\Api\Data;

/**
 * Interface ProfileMapInterface
 * @package ACedraz\CustomerImport\Api\Data
 */
interface ProfileMapInterface
{
    /** @var string */
    const UPDATED_AT = 'updated_at';

    /** @var string */
    const PROFILE_ID = 'profile_id';

    /** @var string */
    const ATTRIBUTE = 'attribute';

    /** @var string */
    const CREATED_AT = 'created_at';

    /** @var string */
    const COLUMN = 'column';

    /** @var string */
    const ENTITY_ID = 'entity_id';

    /**
     * Get profile_id
     * @return string|null
     */
    public function getProfileId();

    /**
     * Set profile_id
     * @param string $profileId
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface
     */
    public function setProfileId($profileId);

    /**
     * Get column
     * @return string|null
     */
    public function getColumn();

    /**
     * Set column
     * @param string $column
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface
     */
    public function setColumn($column);

    /**
     * Get attribute
     * @return string|null
     */
    public function getAttribute();

    /**
     * Set attribute
     * @param string $attribute
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface
     */
    public function setAttribute($attribute);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface
     */
    public function setUpdatedAt($updatedAt);
}
