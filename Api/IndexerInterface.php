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

namespace ACedraz\CustomerImport\Api;

/**
 * Interface IndexerInterface
 * @package ACedraz\CustomerImport\Api
 */
interface IndexerInterface
{
    /**
     * @return void
     */
    public function reindexAll();

    /**
     * @param string $indexId
     * @return void
     */
    public function reindex(string $indexId);
}
