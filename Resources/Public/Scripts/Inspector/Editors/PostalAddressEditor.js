define(
    [
        'emberjs',
        'text!./PostalAddressEditor.html'
    ],
    function (Ember, template) {

        function addressPartProperty(propertyName) {
            return function (key, value) {
                var adresse = this.getPostalAddress();
                // write
                if (arguments.length == 2) {
                    adresse[propertyName] = value;
                    adresse.latitude = adresse.longitude = null;
                    this.set('value', JSON.stringify(adresse));
                }
                // read
                return adresse[propertyName];
            }.property('value');
        }

        return Ember.View.extend({
            template: Ember.Handlebars.compile(template),
            value: '',
            showPreview: true,
            hasCoordinates: function() {
                var adresse = this.getPostalAddress();
                return adresse.latitude && adresse.longitude;
            }.property('value'),

            preview: function() {
                var address = this.getPostalAddress();
                var addressParts = [];
                if (address.streetAddress) {
                    addressParts.push(address.streetAddress);
                }
                if (address.postOfficeBoxNumber) {
                    addressParts.push(address.postOfficeBoxNumber);
                }
                if (address.postalCode) {
                    var postalCodeAndLocality = address.postalCode;
                    if (address.addressLocality) {
                        postalCodeAndLocality += ' ' + address.addressLocality;
                    }
                    addressParts.push(postalCodeAndLocality);
                }
                if (address.addressCountry) {
                    addressParts.push(address.addressCountry);
                }
                return addressParts.length > 0 ? addressParts.join(', ') : '-';
            }.property('value'),

            emberTextField: Ember.TextField,

            getPostalAddress: function() {
                var address = JSON.parse(this.get('value') || '{}');
                // if (address.addressLocality && !address.country) {
                //     address.land = 'Deutschland';
                // }
                return address;
            },

            streetAddress: addressPartProperty('streetAddress'),
            postOfficeBoxNumber: addressPartProperty('postOfficeBoxNumber'),
            addressLocality: addressPartProperty('addressLocality'),
            postalCode: addressPartProperty('postalCode'),
            addressCountry: addressPartProperty('addressCountry'),

            showPreview: function() {
                this.set('showPreview', false);
            }
        });
    });