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

namespace ACedraz\CustomerImport\Model;

/**
 * Class Config
 * @package ACedraz\CustomerImport\Model
 */
class Config
{
    /** @var string */
    const SECTION_CUSTOMERIMPORT = 'customerimport';

    /** @var string */
    const GROUP_UPLOAD = 'upload';

    /** @var string */
    const GROUP_CUSTOMER = 'customer';

    /** @var string */
    const GROUP_ADDRESS = 'address';

    /** @var string */
    const GROUP_COMPOSITE = 'composite';

    /** @var string */
    const FIELD_IMPORT_BEHAVIOR = 'import_behavior';

    /** @var string */
    const SECTION_CUSTOMERIMPORT_GROUP_CUSTOMER = self::SECTION_CUSTOMERIMPORT . DIRECTORY_SEPARATOR . self::GROUP_CUSTOMER . DIRECTORY_SEPARATOR;

    /** @var string */
    const SECTION_CUSTOMERIMPORT_GROUP_ADDRESS = self::SECTION_CUSTOMERIMPORT . DIRECTORY_SEPARATOR . self::GROUP_ADDRESS . DIRECTORY_SEPARATOR;

    /** @var string */
    const SECTION_CUSTOMERIMPORT_GROUP_COMPOSITE = self::SECTION_CUSTOMERIMPORT . DIRECTORY_SEPARATOR . self::GROUP_COMPOSITE . DIRECTORY_SEPARATOR;

    /** @var string */
    const SECTION_CUSTOMERIMPORT_GROUP_CUSTOMER_FIELD_IMPORT_BEHAVIOR = self::SECTION_CUSTOMERIMPORT_GROUP_CUSTOMER . self::FIELD_IMPORT_BEHAVIOR;

    /** @var string */
    const SECTION_CUSTOMERIMPORT_GROUP_ADDRESS_FIELD_IMPORT_BEHAVIOR = self::SECTION_CUSTOMERIMPORT_GROUP_ADDRESS . self::FIELD_IMPORT_BEHAVIOR;

    /** @var string */
    const SECTION_CUSTOMERIMPORT_GROUP_COMPOSITE_FIELD_IMPORT_BEHAVIOR = self::SECTION_CUSTOMERIMPORT_GROUP_COMPOSITE . self::FIELD_IMPORT_BEHAVIOR;
}
