<template>
  <form @submit.prevent="calculateRates()">
    <div>
        <p>Cena netto</p>
        <input type="text" v-model="premiumCalculator.nettoPrice">
    </div>
    <div>
        <p>Cena brutto</p>
        <input type="text" v-model="premiumCalculator.nettoPrice">
    </div>
    <div>
        <p>Rok produkcji samochodu</p>
        <input type="text" v-model="premiumCalculator.nettoPrice">
    </div>
    <div>
        <p>Czy GPS ma być zawarty</p>
        <input type="text" v-model="premiumCalculator.nettoPrice">
    </div>
    <div>
        <p>Na ile rat podzielić</p>
        <input type="text" v-model="premiumCalculator.nettoPrice">
    </div>
    <button>OBLICZ</button>
  </form>
</template>

<script>
import axios from 'axios';

export default {
    name: 'HelloVue',
    data() {
        return {
            premiumCalculator: {
                nettoPrice: '',
                grossPrice: '',
                yearProduction: '',
                gpsIncluded: true,
                ratesCount: 1
            }
        }
    },
    methods: {
        testMethod() {
            axios.post('/api/test')
                .then((data) => {
                    console.log(data.data)
                })
        },
        calculateRates() {
            axios.post('/api/calculate', this.premiumCalculator)
                .then((data) => {
                    console.log(data.data)
                })
        }
    }
}
</script>