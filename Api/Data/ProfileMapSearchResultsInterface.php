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

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ProfileMapSearchResultsInterface
 * @package ACedraz\CustomerImport\Api\Data
 */
interface ProfileMapSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get customer_import_profile list.
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param \ACedraz\CustomerImport\Api\Data\ProfileMapInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
