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

namespace ACedraz\CustomerImport\Model\Data;

use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ProfileMap
 * @package ACedraz\CustomerImport\Model\Data
 */
class ProfileMap extends AbstractModel implements ProfileMapInterface
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\ACedraz\CustomerImport\Model\ResourceModel\ProfileMap::class);
    }

    /**
     * @inheritDoc
     */
    public function getProfileId()
    {
        return $this->getData(self::PROFILE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProfileId($profileId)
    {
        return $this->setData(self::PROFILE_ID, $profileId);
    }

    /**
     * @inheritDoc
     */
    public function getColumn()
    {
        return $this->getData(self::COLUMN);
    }

    /**
     * @inheritDoc
     */
    public function setColumn($column)
    {
        return $this->setData(self::COLUMN, $column);
    }

    /**
     * @inheritDoc
     */
    public function getAttribute()
    {
        return $this->getData(self::ATTRIBUTE);
    }

    /**
     * @inheritDoc
     */
    public function setAttribute($attribute)
    {
        return $this->setData(self::ATTRIBUTE, $attribute);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
