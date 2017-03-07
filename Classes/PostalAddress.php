<?php
namespace Wwwision\Neos\AddressEditor;

use Neos\Flow\Annotations as Flow;

final class PostalAddress implements \JsonSerializable, \ArrayAccess
{
    /**
     * The street address. For example, 1600 Amphitheatre Pkwy
     *
     * @var string
     */
    public $streetAddress;

    /**
     * The post office box number for PO box addresses
     *
     * @var string
     */
    public $postOfficeBoxNumber;

    /**
     * The locality. For example, Mountain View
     *
     * @var string
     */
    public $addressLocality;

    /**
     * The postal code. For example, 94043
     *
     * @var string
     */
    public $postalCode;

    /**
     * The country. For example, USA
     *
     * @var string
     */
    public $addressCountry;

    /**
     * @var double
     */
    public $latitude;

    /**
     * @var double
     */
    public $longitude;

    /**
     * @param string $propertyName
     * @return bool
     */
    public function offsetExists($propertyName)
    {
        return property_exists($this, $propertyName);
    }

    /**
     * @param string $propertyName
     * @return string
     */
    public function offsetGet($propertyName)
    {
        return $this->{$propertyName};
    }

    /**
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function offsetSet($propertyName, $propertyValue)
    {
        $this->{$propertyName} = $propertyValue;
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->{$propertyName});
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        $addressParts = [];
        if (!empty($this->streetAddress)) {
            $addressParts[] = trim($this->streetAddress);
        }
        if (!empty($this->postOfficeBoxNumber)) {
            $addressParts[] = trim($this->postOfficeBoxNumber);
        }
        if (!empty($this->postalCode) || !empty($this->addressLocality)) {
            $postalCodeAndLocalityParts = [];
            if (!empty($this->postalCode)) {
                $postalCodeAndLocalityParts[] = trim($this->postalCode);
            }
            if (!empty($this->addressLocality)) {
                $postalCodeAndLocalityParts[] = trim($this->addressLocality);
            }
            $addressParts[] = implode(' ', $postalCodeAndLocalityParts);
        }
        if (!empty($this->addressCountry)) {
            $addressParts[] = trim($this->addressCountry);
        }
        return implode(', ', $addressParts);
    }
}