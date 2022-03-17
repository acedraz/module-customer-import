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

namespace ACedraz\CustomerImport\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class CustomerImportColumns
 * @package ACedraz\CustomerImport\Model\Config\Source
 */
class CustomerImportColumns implements OptionSourceInterface
{
    /** @var string */
    const ADDRESS_DEFAULT_SHIPPING = 'Address Default Shipping';

    /** @var string */
    const ADDRESS_DEFAULT_SHIPPING_VALUE = '_address_default_shipping_';

    /** @var string */
    const ADDRESS_DEFAULT_BILLING = 'Address Default Billing';

    /** @var string */
    const ADDRESS_DEFAULT_BILLING_VALUE = '_address_default_billing_';

    /** @var string */
    const ADDRESS_VAT_ID = 'Address Vat ID';

    /** @var string */
    const ADDRESS_VAT_ID_VALUE = '_address_vat_id';

    /** @var string */
    const ADDRESS_TELEPHONE = 'Address Telephone';

    /** @var string */
    const ADDRESS_TELEPHONE_VALUE = '_address_telephone';

    /** @var string */
    const ADDRESS_SUFFIX = 'Address Suffix';

    /** @var string */
    const ADDRESS_SUFFIX_VALUE = '_address_suffix';

    /** @var string */
    const ADDRESS_STREET = 'Address Street';

    /** @var string */
    const ADDRESS_STREET_VALUE = '_address_street';

    /** @var string */
    const ADDRESS_REGION = 'Address Region';

    /** @var string */
    const ADDRESS_REGION_VALUE = '_address_region';

    /** @var string */
    const ADDRESS_PREFIX = 'Address Prefix';

    /** @var string */
    const ADDRESS_PREFIX_VALUE = '_address_prefix';

    /** @var string */
    const ADDRESS_POSTCODE = 'Address Postcode';

    /** @var string */
    const ADDRESS_POSTCODE_VALUE = '_address_postcode';

    /** @var string */
    const ADDRESS_MIDDLENAME = 'Address Middlename';

    /** @var string */
    const ADDRESS_MIDDLENAME_VALUE = '_address_middlename';

    /** @var string */
    const ADDRESS_LASTNAME = 'Address Lastname';

    /** @var string */
    const ADDRESS_LASTNAME_VALUE = '_address_lastname';

    /** @var string */
    const ADDRESS_FIRSTNAME = 'Address Firstname';

    /** @var string */
    const ADDRESS_FIRSTNAME_VALUE = '_address_firstname';

    /** @var string */
    const ADDRESS_FAX = 'Address Fax';

    /** @var string */
    const ADDRESS_FAX_VALUE = '_address_fax';

    /** @var string */
    const ADDRESS_COUNTRY_ID = 'Address Country Id';

    /** @var string */
    const ADDRESS_COUNTRY_ID_VALUE = '_address_country_id';

    /** @var string */
    const ADDRESS_COMPANY = 'Address Company';

    /** @var string */
    const ADDRESS_COMPANY_VALUE = '_address_company';

    /** @var string */
    const ADDRESS_CITY = 'Address City';

    /** @var string */
    const ADDRESS_CITY_VALUE = '_address_city';

    /** @var string */
    const PASSWORD = 'Password';

    /** @var string */
    const PASSWORD_VALUE = 'password';

    /** @var string */
    const WEBSITE_ID = 'Website Id';

    /** @var string */
    const WEBSITE_ID_VALUE = 'website_id';

    /** @var string */
    const TAXVAT = 'Taxvat';

    /** @var string */
    const TAXVAT_VALUE = 'taxvat';

    /** @var string */
    const SUFFIX = 'Suffix';

    /** @var string */
    const SUFFIX_VALUE = 'suffix';

    /** @var string */
    const STORE_ID = 'Store Id';

    /** @var string */
    const STORE_ID_VALUE = 'store_id';

    /** @var string */
    const RP_TOKEN_CREATED_AT = 'RP Token Created At';

    /** @var string */
    const RP_TOKEN_CREATED_AT_VALUE = 'rp_token_created_at';

    /** @var string */
    const RP_TOKEN = 'RP Token';

    /** @var string */
    const RP_TOKEN_VALUE = 'rp_token';

    /** @var string */
    const PREFIX = 'Prefix';

    /** @var string */
    const PREFIX_VALUE = 'prefix';

    /** @var string */
    const PASSWORD_HASH = 'Password Hash';

    /** @var string */
    const PASSWORD_HASH_VALUE = 'password_hash';

    /** @var string */
    const MIDDLENAME = 'Middlename';

    /** @var string */
    const MIDDLENAME_VALUE = 'middlename';

    /** @var string */
    const LASTNAME = 'Lastname';

    /** @var string */
    const LASTNAME_VALUE = 'lastname';

    /** @var string */
    const GROUP_ID = 'Group Id';

    /** @var string */
    const GROUP_ID_VALUE = 'group_id';

    /** @var string */
    const FIRSTNAME = 'Firstname';

    /** @var string */
    const FIRSTNAME_VALUE = 'firstname';

    /** @var string */
    const GENDER = 'Gender';

    /** @var string */
    const GENDER_VALUE = 'gender';

    /** @var string */
    const DOB = 'DOB';

    /** @var string */
    const DOB_VALUE = 'dob';

    /** @var string */
    const DISABLE_AUTO_GROUP_CHANGE = 'Disable Auto Group Change';

    /** @var string */
    const DISABLE_AUTO_GROUP_CHANGE_VALUE = 'disable_auto_group_change';

    /** @var string */
    const CREATED_IN = 'Created In';

    /** @var string */
    const CREATED_IN_VALUE = 'created_in';

    /** @var string */
    const CREATED_AT = 'Created At';

    /** @var string */
    const CREATED_AT_VALUE = 'created_at';

    /** @var string */
    const CONFIRMATION = 'Confirmation';

    /** @var string */
    const CONFIRMATION_VALUE = 'confirmation';

    /** @var string */
    const EMAIL = 'Email';

    /** @var string */
    const EMAIL_VALUE = 'email';

    /** @var string */
    const WEBSITE = 'Website';

    /** @var string */
    const WEBSITE_VALUE = '_website';

    /** @var string */
    const STORE = 'Store';

    /** @var string */
    const STORE_VALUE = '_store';

    /**
     * @var string[]
     */
    protected array $_address = [
        self::ADDRESS_CITY_VALUE => self::ADDRESS_CITY,
        self::ADDRESS_COMPANY_VALUE => self::ADDRESS_COMPANY,
        self::ADDRESS_COUNTRY_ID_VALUE => self::ADDRESS_COUNTRY_ID,
        self::ADDRESS_FAX_VALUE => self::ADDRESS_FAX,
        self::ADDRESS_FIRSTNAME_VALUE => self::ADDRESS_FIRSTNAME,
        self::ADDRESS_LASTNAME_VALUE => self::ADDRESS_LASTNAME,
        self::ADDRESS_MIDDLENAME_VALUE => self::ADDRESS_MIDDLENAME,
        self::ADDRESS_POSTCODE_VALUE => self::ADDRESS_POSTCODE,
        self::ADDRESS_PREFIX_VALUE => self::ADDRESS_PREFIX,
        self::ADDRESS_REGION_VALUE => self::ADDRESS_REGION,
        self::ADDRESS_STREET_VALUE => self::ADDRESS_STREET,
        self::ADDRESS_SUFFIX_VALUE => self::ADDRESS_SUFFIX,
        self::ADDRESS_TELEPHONE_VALUE => self::ADDRESS_TELEPHONE,
        self::ADDRESS_VAT_ID_VALUE => self::ADDRESS_VAT_ID,
        self::ADDRESS_DEFAULT_BILLING_VALUE => self::ADDRESS_DEFAULT_BILLING,
        self::ADDRESS_DEFAULT_SHIPPING_VALUE => self::ADDRESS_DEFAULT_SHIPPING
    ];

    /**
     * @var string[]
     */
    protected array $_customer = [
        self::EMAIL_VALUE => self::EMAIL,
        self::WEBSITE_VALUE => self::WEBSITE,
        self::STORE_VALUE => self::STORE,
        self::CONFIRMATION_VALUE => self::CONFIRMATION,
        self::CREATED_AT_VALUE => self::CREATED_AT,
        self::CREATED_IN_VALUE => self::CREATED_IN,
        self::DISABLE_AUTO_GROUP_CHANGE_VALUE => self::DISABLE_AUTO_GROUP_CHANGE,
        self::DOB_VALUE => self::DOB,
        self::FIRSTNAME_VALUE => self::FIRSTNAME,
        self::GENDER_VALUE => self::GENDER,
        self::GROUP_ID_VALUE => self::GROUP_ID,
        self::LASTNAME_VALUE => self::LASTNAME,
        self::MIDDLENAME_VALUE => self::MIDDLENAME,
        self::PASSWORD_HASH_VALUE => self::PASSWORD_HASH,
        self::PREFIX_VALUE => self::PREFIX,
        self::RP_TOKEN_VALUE => self::RP_TOKEN,
        self::RP_TOKEN_CREATED_AT_VALUE => self::RP_TOKEN_CREATED_AT,
        self::STORE_ID_VALUE => self::STORE_ID,
        self::SUFFIX_VALUE => self::SUFFIX,
        self::TAXVAT_VALUE => self::TAXVAT,
        self::WEBSITE_ID_VALUE => self::WEBSITE_ID,
        self::PASSWORD_VALUE => self::PASSWORD,
    ];

    /**
     * @return array
     */
    public function getCustomerComposite(): array
    {
        return array_merge($this->getCustomerOptions(), $this->getAddressOptions());
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->getCustomerComposite() as $code => $label) {
            $options[] = ['value' => $code, 'label' => $label];
        }
        return $options;
    }

    /**
     * @return string[]
     */
    public function getCustomerOptions(): array
    {
        return $this->_customer;
    }

    /**
     * @return string[]
     */
    public function getAddressOptions(): array
    {
        return $this->_address;
    }

}
