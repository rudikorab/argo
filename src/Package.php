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
        $label_ocr = $this->$label_ocr;
        
        $carrier_code  = null;
        $provider_code = null;
        
        
        
        
	    $matchDHL1      = '~^[0-9]{2}[0-9]{4}[0-9]{4}$~';
	    
	    $matchUPS1      = '~b(1Z ?[0-9A-Z]{3} ?[0-9A-Z]{3} ?[0-9A-Z]{2} ?[0-9A-Z]{4} ?[0-9A-Z]{3} ?[0-9A-Z]|[\dT]\d\d\d ?\d\d\d\d ?\d\d\d)\b~';
	    $matchUPS2      = '~^[kKJj]{1}[0-9]{10}$~';
	    $matchUPS3      = '~^1Z[A-Z0-9]{3}[A-Z0-9]{3}[0-9]{2}[0-9]{4}[0-9]{4}$/i~';
	
	    $matchUSPS1     = '~(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)~';
	    $matchUSPS2     = '~(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)|(\b\d{26}\b)| ^E\D{1}\d{9}\D{2}$|^9\d{15,21}$| ^91[0-9]+$| ^[A-Za-z]{2}[0-9]+US$/i~';
	    $matchUSPS3     = '~^E\D{1}\d{9}\D{2}$|^9\d{15,21}$~';
	    $matchUSPS4     = '~^91[0-9]+$~';
	    $matchUSPS5     = '~^[A-Za-z]{2}[0-9]+US$~';
	    $matchUSPS6     = '~(\b\d{30}\b)|(\b91\d+\b)|(\b\d{20}\b)|(\b\d{26}\b)| ^E\D{1}\d{9}\D{2}$|^9\d{15,21}$| ^91[0-9]+$| ^[A-Za-z]{2}[0-9]+US$/i~';
	    $matchUSPS7     = '~^[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2}$~';
		$matchUSPS8     = '~^420[0-9]{5}([0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{2})$~';
	
	    $matchFedex1    = '~^[1-9]{4}[0-9]{4}[0-9]{4}$~';
	    $matchFedex2    = '~(\b96\d{20}\b)|(\b\d{15}\b)|(\b\d{12}\b)~';
	    $matchFedex3    = '~\b((98\d\d\d\d\d?\d\d\d\d|98\d\d) ?\d\d\d\d ?\d\d\d\d( ?\d\d\d)?)\b~';
	    $matchFedex4    = '~^[0-9]{15}$~';
	    
	    $matchLaser = '~^LT[0-9]{8}|LE[0-9]{8}|1L[0-9]{8}+$/i~';

	    $matchAmazon = '~^TBA[0-9]{12}+$~';
	    
	    $matchRoyalmail = '~^[A-Za-z]{2}[0-9]+GB$/i~';
	    
	    $matchChinapost1 = '~^R\D{1}[0-9]{9}+CN$/i~';
	    $matchChinapost2 = '~^E\D{1}[0-9]{9}+CN$/i~';
	    
	    $matchCapost = '~^[0-9]{16}$|^[A-Z]{2}[0-9]{9}[A-Z]{2}$~';
	    

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
	              preg_match($matchUSPS7, $tracking_code) ||
	              preg_match($matchUSPS8, $tracking_code)) {
			$carrier_code = Carrier::CODE_USPS;
	    } else if (preg_match($matchFedex1, $tracking_code) || 
	               preg_match($matchFedex2, $tracking_code) ||
	               preg_match($matchFedex3, $tracking_code) || 
	               preg_match($matchFedex4, $tracking_code)) {
			$carrier_code = Carrier::CODE_FEDEX;
	    } else if (preg_match($matchDHL1, $tracking_code)) {
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
	    }
	    
	    
	    
	    





        if (!empty($carrier_code)) {
            $this->carrier = new Carrier($carrier_code);
            $this->provider = new Provider($provider_code ?: $carrier_code);
        }

        return $this;
    }
}
