<?php

namespace Argo;

/**
 * Handles carrier interactions.
 */
class Carrier
{
    /**
     * DHL carrier code.
     */
    const CODE_DHL = 'dhl';

    /**
     * FedEx carrier code.
     */
    const CODE_FEDEX = 'fedex';

    /**
     * UPS carrier code.
     */
    const CODE_UPS = 'ups';

    /**
     * USPS carrier code.
     */
    const CODE_USPS = 'usps';

    /**
     * LASERSHIP carrier code.
     */
    const CODE_LASERSHIP = 'lasership';

    /**
     * ROYALMAIL carrier code.
     */
    const CODE_ROYALMAIL = 'royalmail';
   
    /**
     * CHINAPOST carrier code.
     */
    const CODE_CHINAPOST = 'chinapost';
   
    /**
     * CANADAPOST carrier code.
     */
    const CODE_CANADAPOST = 'canadapost';
   
    /**
     * AMAZON carrier code.
     */
    const CODE_AMAZON = 'amazon';




    /**
     * Carrier code.
     *
     * @var string
     */
    public $code;

    /**
     * Carrier display name.
     *
     * @var string.
     */
    public $name;

    /**
     * Supported carriers.
     *
     * @var array
     */
    private static $carriers = [
        self::CODE_DHL => 'DHL',
        self::CODE_FEDEX => 'FedEx',
        self::CODE_UPS => 'UPS',
        self::CODE_USPS => 'USPS',
        self::CODE_AMAZON => 'AMAZON',
        self::CODE_LASERSHIP => 'LASERSHIP',
        self::CODE_ROYALMAIL => 'ROYALMAIL',
        self::CODE_CHINAPOST => 'CHINAPOST',
        self::CODE_CANADAPOST => 'CANADAPOST',
        
        
    ];

    /**
     * Initializes the class.
     *
     * @param string $code The carrier code.
     *
     * @return void
     */
    public function __construct(string $code)
    {
        if (!array_key_exists($code, self::$carriers)) {
            return false;
        }

        $this->code = $code;
        $this->name = self::$carriers[$code];
    }

    /**
     * Gets all supported carriers.
     *
     * @return array
     */
    public static function getAll(): array
    {
        return self::$carriers;
    }
}
