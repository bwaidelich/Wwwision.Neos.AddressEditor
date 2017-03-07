<?php
namespace Wwwision\Neos\AddressEditor;

use Geocoder\Exception\ChainNoResult;
use Geocoder\Geocoder;
use Geocoder\Model\Coordinates;
use Geocoder\Provider\Chain;
use Geocoder\Provider\GoogleMaps;
use Geocoder\ProviderAggregator;
use Ivory\HttpAdapter\Guzzle6HttpAdapter;
use Neos\Flow\Annotations as Flow;

/**
 * Central authority for GeoCoding related concerns
 *
 * @Flow\Scope("singleton")
 */
final class GeoCodingService
{

    /**
     * @var Geocoder
     */
    private $geocoder;

    public function __construct(string $googleMapsApiKey)
    {
        $this->geocoder = new ProviderAggregator();
        $adapter  = new Guzzle6HttpAdapter();

        $chain = new Chain([
            new GoogleMaps($adapter, null, null, true, $googleMapsApiKey),
            // To enable further fallbacks / different implementations, uncomment the following:
            // new FreeGeoIp($adapter),
            // new HostIp($adapter),
            // new BingMaps($adapter, $bingMapsApiKey)
        ]);
        $this->geocoder->registerProvider($chain);
    }

    /**
     * @param PostalAddress $postalAddress
     * @return Coordinates
     */
    public function getCoordinatesForPostalAddress(PostalAddress $postalAddress)
    {
        try {
            $addresses = $this->geocoder->geocode((string)$postalAddress);
        } catch (ChainNoResult $e) {
            return null;
        }
        $firstAddress = $addresses->first();
        if ($firstAddress === false) {
            return null;
        }
        return $firstAddress->getCoordinates();
    }


}