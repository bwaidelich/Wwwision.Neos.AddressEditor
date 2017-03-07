# Wwwision.Neos.AddressEditor

Simple Neos extension adding a custom property editor for postal addresses and GeoCoding support

## Features

This package comes with two main features:

1. A custom property editor for `PostalAddress`-properties
2. A `GeoCoding` service that can look up GeoCoordinates by Postal- and IP Addresses (based on the great [willdurand/geocoder](https://packagist.org/packages/willdurand/geocoder) package)

## Usage

Install the package using [composer](https://getcomposer.org/):

```
composer require wwision/neos-addresseditor:^1.0
```

After successful installation you should configure a [Google Maps API key](https://developers.google.com/maps/documentation/javascript/get-api-key):

```yaml
Wwwision:
  Neos:
    AddressEditor:
      googleMapsApiKey: '<VALID_GOOGLE_MAPS_API_KEY>'
```

(For testing purposes you should be able to skip that step)

To make use of the new Property Editor, just add a property of `PostalAddress` to any NodeType configuration:

```yaml
'Some.Package:SomeNodeType':
  # ...
  properties:
    'someProperty':
      type: 'Wwwision\Neos\AddressEditor\PostalAddress'
```

Afterwards you should be able to see the new `PostalAddressEditor` in the Neos Backend:

![Screenshot expanded editor](/Screenshot_1.png "Screenshot expanded editor")
![Screenshot collapsed editor](/Screenshot_2.png "Screenshot collapsed editor")

*Note:* The green checkmark indicates that the geocoding for this address has been successful

## Display coordinates

When the geocoding was successful you can access the coordinates via the `longitude` and `latitude` fields of the address property.
With a little bit of `Fusion` code you can make these available within a template (i.e. for rendering them in a Map):

```
prototype(Some.Package:SomeNodeType) < prototype(Neos.Fusion:Template) {
    templatePath = 'resource://Some.Package/Private/Templates/FusionObjects/Map.html'

    address = ${q(node).property('address')}
}
```

In the Fluid template you can then access the coordinates like this:

```html
<f:if condition="{address}">
    <span class="marker" data-latitude="{address.latitude}" data-longitude="{address.longitude}">{address}</span>
</f:if>
```

## License

Licensed under MIT, see [LICENSE](LICENSE)
