define([
    'jquery',
    'uiComponent',
    'ko',
    'mage/url'
], function ($, Component, ko, urlBuilder) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Razoyo_CarProfile/search-form'
        },
        searchCarUrl: window.searchCarUrl,
        saveCarUrl: window.saveCarUrl,
        error: 0,
        header: 1,
        body: 2,

        initialize: function () {
            this.searchResults = ko.observableArray([]);
            this.makes = ko.observableArray([]);
            this.carId = ko.observable();
            this.emptySearch = ko.observable(false);
            this._populateMakes();
            this._super()
        },

        searchByMake: function () {
            $('.search-results-wrapper').trigger('processStart');
            $.ajax({
                url: window.searchCarUrl,
                type: 'GET',
                context: this,
                dataType: 'json',
                data: {
                    make: $('#mycarsearch').val()
                },
                success: function (response) {
                    $('.search-results-wrapper').trigger('processStop');
                    var cars = JSON.parse(response[this.body]).cars;
                    if (!Array.isArray(cars) || !cars.length) {
                        this.searchResults([]);
                        this.emptySearch(true);
                    } else {
                        this.emptySearch(false);
                        this.searchResults(cars);
                        this._handleClickEvents();
                    }

                },
                error: function () {
                   console.log('Error searching for cars has occurred.')
                    $('.search-results-wrapper').trigger('processStart');
                }
            })
        },

        saveCar: function () {
            var carId = $('input[name="make"]:checked').val();
            $('.search-results-wrapper').trigger('processStart');
            $.ajax({
                url: window.saveCarUrl + '/' + carId,
                type: 'POST',
                context: this,
                dataType: 'json',
                success: function (response) {
                    $('.search-results-wrapper').trigger('processStop');
                },
                error: function (jqXHR) {
                    $('.search-results-wrapper').trigger('processStop');
                    if (jqXHR.status === 401) {
                        window.location.href = urlBuilder.build('customer/account/login');
                    }
                    console.log('Error saving car preference.');
                }
            })
        },

        _populateMakes: function () {
            $.ajax({
                url: window.searchCarUrl,
                type: 'GET',
                context: this,
                dataType: 'json',
                success: function (response) {
                    var makes = JSON.parse(response[this.body]).makes;
                    if (!Array.isArray(makes) || !makes.length) {
                        this.makes([]);
                    } else {
                        this.makes(makes);
                    }

                },
                error: function () {
                    console.log('Error searching for cars has occurred.')
                }
            })
        },

        _handleClickEvents: function () {
            $('.car-selection-wrapper').on('click', function(event) {
                $(event.target).children('input').prop('checked', true);

                var disabledAttr = $('#submit-car-preference-button').attr('disabled');
                if (typeof disabledAttr !== 'undefined' && disabledAttr !== false) {
                    $('#submit-car-preference-button').removeAttr('disabled');
                }
            })
        }
    })
})