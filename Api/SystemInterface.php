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

/**
 * Interface SystemInterface
 * @package ACedraz\CustomerImport\Api
 */
interface SystemInterface
{
    /**
     * @return string
     */
    public function getCustomerBehavior(): string;

    /**
     * @return string
     */
    public function getAddressBehavior(): string;

    /**
     * @return string
     */
    public function getCompositeBehavior(): string;
}
