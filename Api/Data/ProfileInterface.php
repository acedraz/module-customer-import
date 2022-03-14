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

namespace ACedraz\CustomerImport\Api\Data;

/**
 * Interface ProfileInterface
 * @package ACedraz\CustomerImport\Api\Data
 */
interface ProfileInterface
{
    /** @var string */
    const ENABLE = 'enable';

    /** @var string */
    const ENTITY_ID = 'entity_id';

    /** @var string */
    const NAME = 'name';

    /** @var string */
    const MAP = 'map';

    /** @var string */
    const UPDATED_AT = 'updated_at';

    /** @var string */
    const CREATED_AT = 'created_at';

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface
     */
    public function setName($name);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface
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
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get enable
     * @return bool|null
     */
    public function getEnable();

    /**
     * Set enable
     * @param bool $enable
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface
     */
    public function setEnable($enable);

    /**
     * Get map
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface[]|null
     */
    public function getMap();

    /**
     * Set map
     * @param \ACedraz\CustomerImport\Api\Data\ProfileMapInterface[] $map
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface
     */
    public function setMap(array $map);
}
