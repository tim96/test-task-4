import Vue from 'vue'

new Vue({
    el: '#app',
    data() {
        return {
            results: [],
            cart: [],
            orderId: null,
            paySum: null
        };
    },
    mounted () {
        this.getProducts();
    },
    methods: {
        generateData: function () {
            var self = this;
            axios
                .post('/api/products')
                .then(function () {
                    console.log('Products were generated');

                    self.getProducts();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getProducts: function () {
            var self = this;
            axios
                .get('/api/products')
                .then(function (response) {
                    self.results = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        addToCart: function (productId) {
            var item = this.results.find(x => x.id === productId);
            if (item && !this.cart.find(x => x.id === productId)) {
                this.cart.push(item);
            } else if (this.cart.find(x => x.id === productId)) {
                console.log('item already in cart');
            }
        },
        sumCart: function () {
            var total = 0;

            for(var i = 0, count = this.cart.length; i < count; i++) {
                total += parseInt(this.cart[i].price);
            }

            return total;
        },
        createOrder: function () {
            var self = this;
            axios
                .post('/api/orders', this.cart)
                .then(function (response) {
                    self.orderId = response.data.orderId;
                    self.paySum = response.data.sum;
                    self.cart = [];

                    $('#shoppingCart').modal('hide');
                    $('#payModal').modal('show');
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        payOrder: function (orderId, paySum) {
            var self = this;
            axios
                .post('/api/orders/pay', {
                    sum: self.paySum,
                    orderId: self.orderId
                })
                .then(function (response) {
                    self.orderId = null;
                    self.sum = null;

                    $('#shoppingCart').modal('hide');
                    $('#payModal').modal('hide');
                })
                .catch(function (error) {
                    console.log(error);
                });

            console.log('paySum', paySum);
            console.log('orderId', orderId);
        }
    }
})
