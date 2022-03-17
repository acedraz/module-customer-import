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

namespace ACedraz\CustomerImport\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Handler
 * @package ACedraz\CustomerImport\Logger
 */
class Handler extends Base
{
    /** @var string */
    const DIRECTORY_VAR_LOG = '/var/log/';

    /** @var string */
    const FILE_NAME = 'acedraz_customerimport.log';

    /**
     * File name
     * @var string
     */
    protected $fileName = self::DIRECTORY_VAR_LOG . self::FILE_NAME;

    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;
}
