define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/action/get-totals',
        'mage/url',
        'mage/storage',
        'jquery',
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        customer,
        getTotalsAction,
        urlBuilder,
        storage,
        jQuery
    ) {
        'use strict';
        /**
        * check-login - is the name of the component's .html template
        */
        return Component.extend({
            defaults: {
                template: 'Brainvire_Checkoutstep/check-login'
            },

            productList : ko.observableArray([]),
            //add here your logic to display step,
            isVisible: ko.observable(true),
            message: ko.observable(''),
            isLogedIn: customer.isLoggedIn(),
            //step code will be used as step content id in the component template
            stepCode: 'isLogedCheck',
            //step title value
            stepTitle: 'Logging Status',

            /**
            *
            * @returns {*}
            */
            initialize: function () {
                this._super();
                this.getProduct();
                // register your step
                stepNavigator.registerStep(
                    this.stepCode,
                    //step alias
                    null,
                    this.stepTitle,
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                    * sort order value
                    * 'sort order value' < 10: step displays before shipping step;
                    * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                    * 'sort order value' > 20 : step displays after payment step
                    */
                    15
                );

                return this;
            },

            /**
            * The navigate() method is responsible for navigation between checkout step
            * during checkout. You can add custom logic, for example some conditions
            * for switching to your custom step
            */
            navigate: function () {

            },

            /**
            * @returns void
            */
            navigateToNextStep: function () {
                stepNavigator.next();
            },

            getProduct: function () {
                var self = this;
                var serviceUrl = urlBuilder.build('checkoutstep/index/products');
                return storage.post(
                    serviceUrl,
                    ''
                ).done(
                function (response) {
                        var data = JSON.parse(response);
                        
                        if(data.products){
                            ko.utils.arrayPushAll(self.productList, data.products);
                        }

                        self.message(data.message);
                        //console.log(self.message());
                    }
                ).fail(
                function (response) {
                        alert(response);
                    }
                );
            },

            decreaseQty : function(data,event){
                var event_id = event.target.id;
                var oldVal = jQuery('#'+event_id).closest('tr').find("input.qty").val();
                if (parseFloat(oldVal) >= 2) {
                    var newVal = parseFloat(oldVal) - 1;
                    jQuery('#'+event_id).closest('tr').find("input.qty").val(newVal);
                }
            },

            increaseQty : function(data,event){
                var event_id = event.target.id;
                var oldVal = jQuery('#'+event_id).closest('tr').find("input.qty").val();
                if (parseFloat(oldVal) >= 1) {
                    var newVal = parseFloat(oldVal) + 1;
                    jQuery('#'+event_id).closest('tr').find("input.qty").val(newVal);
                }
            },

            addToCart : function(){
                var self = this;
                var object = self.productList._latestValue;

                var product_entity = jQuery.map(object, function(product) {
                    if(product.entity_id){
                        var qty = jQuery('#'+product.entity_id).closest('tr').find("input.qty").val();
                        product['qty'] = qty;
                        return product;
                    }
                }); 

                var product_id = jQuery.map(product_entity, function(product) {
                    return product.entity_id;
                });
                var test = {};
                var product_qty = jQuery.map(product_entity, function(product,i) {
                    test[product.entity_id] = product.qty;
                    return test;
                });

                jQuery.ajax({
                    url: urlBuilder.build('checkoutstep/index/addtocart'),
                    showLoader: true,
                    data: {'product_entity' : product_id , 'product_qty' : product_qty[0]},
                    type: 'post',
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        var deferred = jQuery.Deferred();
                        getTotalsAction([], deferred);
                    }
                });  
            },
        });
    }
);
