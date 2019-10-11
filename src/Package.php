<?php

namespace Argo;

/**
 * The primary Argo class.
 * Handles package interactions.
 */
class Package
{
    /**
     * Original, provided tracking code.
     *
     * @var string
     */
    public $tracking_code_original;

    /**
     * True tracking code (sans formatting, provider prefix/suffix characters).
     *
     * @var string
     */
    public $tracking_code;

    /**
     * Carrier data.
     *
     * @var Argo\Carrier
     */
    public $carrier;

    /**
     * Provider data.
     *
     * @var Argo\Provider
     */
    public $provider;

    /**
     * Creates a package instance based on a tracking code.
     *
     * @param string $tracking_code The package tracking code.
     *
     * @return Argo\Package
     */
    public static function instance(string $tracking_code): Package
    {
        $instance = new self();
        $instance->tracking_code_original = $tracking_code;
        $instance->tracking_code = preg_replace('/[^A-Z0-9]/i', '', $tracking_code);

        return $instance->deduceTrackingCode();
    }

    /**
     * Gets this package's carrier code.
     *
     * @return string
     */
    public function getCarrierCode(): string
    {
        if (!$this->carrier instanceof Carrier) {
            return '';
        }

        return $this->carrier->code;
    }
    /**
     * Gets this package's carrier name.
     *
     * @return string
     */
    public function getCarrierName(): string
    {
        if (!$this->carrier instanceof Carrier) {
            return '';
        }

        return $this->carrier->name;
    }

    /**
     * Gets this package's provider code.
     *
     * @return string
     */
    public function getProviderCode(): string
    {
        if (!$this->provider instanceof Provider) {
            return '';
        }

        return $this->provider->code;
    }

    /**
     * Gets this package's provider name.
     *
     * @return string
     */
    public function getProviderName(): string
    {
        if (!$this->provider instanceof Provider) {
            return '';
        }

        return $this->provider->name;
    }

    /**
     * Gets the tracking code.
     *
     * @param bool $return_original Whether or not to return the original or true tracking code.
     *
     * @return string
     */
    public function getTrackingCode(bool $return_original = false): string
    {
        return $return_original ? $this->tracking_code_original : $this->tracking_code;
    }

    /**
     * Determines the package's shipping details based on its tracking code.
     *
     * @return Argo\Package
     */
    private function deduceTrackingCode(): Package
    {
        $tracking_code = $this->tracking_code;
        
        $carrier_code  = null;
        $provider_code = null;
        
       
        
        
        
	    $matchDHL1      = '/\b^[0-9]{2}[0-9]{4}[0-9]{4}\b/i';
	    $matchDHL2      = '/\b(\d\d\d\d ?\d\d\d\d ?\d\d)\b/i';
	    
	    $matchUPS1      = '/\b(1Z ?[0-9A-Z]{3} ?[0-9A-Z]{3} ?[0-9A-Z]{2} ?[0-9A-Z]{4} ?[0-9A-Z]{3} ?[0-9A-Z]|[\dT]\d\d\d ?\d\d\d\d ?\d\d\d)\b/i';
	    $matchUPS2      = '/\b^[kKJj]{1}[0-9]{10}\b/i';
	    $matchUPS3      = '/\b^1Z[A-Z0-9]{3}[A-Z0-9]{3}[0-9]{2}[0-9]{4}[0-9]{4}\b/i';
	
	    $matchUSPS1     = '/\b(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)\b/i';
	    $matchUSPS2     = '/\b(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)|(\b\d{26}\b)| ^E\D{1}\d{9}\D{2}$|^9\d{15,21}$| ^91[0-9]+$| ^[A-Za-z]{2}[0-9]+US\b/i';
	    $matchUSPS3     = '/\b^E\D{1}\d{9}\D{2}$|^9\d{15,21}\b/i';
	    $matchUSPS4     = '/\b^91[0-9]\b/i';
	    $matchUSPS5     = '/\b^[A-Za-z]{2}[0-9]+US\b/i';
	    $matchUSPS6     = '/\b(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)|(\b\d{26}\b)| ^E\D{1}\d{9}\D{2}$|^9\d{15,21}$| ^91[0-9]+$| ^[A-Za-z]{2}[0-9]+US\b/i';
	    $matchUSPS7     = '/\b^[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2}\b/i';
		$matchUSPS8     = '/\b^420[0-9]{5}([0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2})\b/i';
	
	    $matchFedex1    = '/\b^[1-9]{4}[0-9]{4}[0-9]{4}\b/i';
	    $matchFedex2    = '/\b(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)\b/i';
	    $matchFedex3    = '/\b\b((98\d\d\d\d\d?\d\d\d\d|98\d\d) ?\d\d\d\d ?\d\d\d\d( ?\d\d\d)?)\b/i';
	    $matchFedex4    = '/\b^[0-9]{15}\b/i';
	    
	    $matchLaser = '/\b^LT[0-9]{8}|LE[0-9]{8}|1L[0-9]{8}\b/i';

	    $matchAmazon = "/\bTBA[0-9]{12}\b/i";
	    
	    $matchRoyalmail = '/\b^[A-Za-z]{2}[0-9]+GB\b/i';
	    
	    $matchChinapost1 = '/\b^R\D{1}[0-9]{9}+CN\b/i';
	    $matchChinapost2 = '/\b^E\D{1}[0-9]{9}+CN\b/i';
	    
	    $matchCapost = '/\b^[0-9]{16}$|^[A-Z]{2}[0-9]{9}[A-Z]{2}\b/i';
	    

	    if(preg_match($matchUPS1, $tracking_code) ||
	    	preg_match($matchUPS2, $tracking_code) ||
	    	preg_match($matchUPS3, $tracking_code)) {
	        $carrier_code = Carrier::CODE_UPS;
	    } else if (preg_match($matchAmazon, $tracking_code)) {
			$carrier_code = Carrier::CODE_AMAZON;
	    } else if(preg_match($matchUSPS1, $tracking_code) || 
	              preg_match($matchUSPS2, $tracking_code) ||
	              preg_match($matchUSPS3, $tracking_code) ||
	              preg_match($matchUSPS4, $tracking_code) ||
	              preg_match($matchUSPS5, $tracking_code) ||
	              preg_match($matchUSPS6, $tracking_code) ||
	              preg_match($matchUSPS7, $tracking_code) 
	              //||
	              //preg_match($matchUSPS8, $tracking_code)
	              ) {
			$carrier_code = Carrier::CODE_USPS;
	    } else if (preg_match($matchFedex1, $tracking_code) || 
	               preg_match($matchFedex2, $tracking_code) ||
	               preg_match($matchFedex3, $tracking_code) || 
	               preg_match($matchFedex4, $tracking_code)) {
			$carrier_code = Carrier::CODE_FEDEX;
	    } else if (preg_match($matchDHL1, $tracking_code) || 
	               preg_match($matchDHL2, $tracking_code)) {
			$carrier_code = Carrier::CODE_DHL;
	    } else if (preg_match($matchLaser, $tracking_code)) {
			$carrier_code = Carrier::CODE_LASERSHIP;
	    } else if (preg_match($matchRoyalmail, $tracking_code)) {
			$carrier_code = Carrier::CODE_ROYALMAIL;
	    } else if (preg_match($matchChinapost1, $tracking_code) || 
	               preg_match($matchChinapost2, $tracking_code)) {
			$carrier_code = Carrier::CODE_CHINAPOST;
	    } else if (preg_match($matchCapost, $tracking_code)) {
			$carrier_code = Carrier::CODE_CANADAPOST;
	    } elseif (preg_match(
            '/^420[0-9]{5}([0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2})$/',
            $tracking_code,
            $matches
        )) {
            $this->tracking_code = $matches[1];
            $carrier_code = Carrier::CODE_USPS;
            $provider_code = Provider::CODE_ENDICIA;
        }
	    
	    
	    





        if (!empty($carrier_code)) {
            $this->carrier = new Carrier($carrier_code);
            $this->provider = new Provider($provider_code ?: $carrier_code);
        }

        return $this;
    }
}
