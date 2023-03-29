<template>
  <form @submit.prevent="calculateRates()">
    <div>
        <p>Cena netto</p>
        <input 
            type="number" 
            step='0.01'
            v-model="premiumCalculator.netPrice"
            @change="calculateGross()"
        >
    </div>
    <div>
        <p>Cena brutto</p>
        <input 
            type="number"
            step='0.01'
            v-model="premiumCalculator.grossPrice"
            @change="calculateNetto()"
        >
    </div>
    <div>
        <p>Rok produkcji samochodu</p>
        <input type="text" v-model="premiumCalculator.yearProduction">
    </div>
    <div>
        <p>Czy GPS ma być zawarty</p>
        <input type="checkbox" v-model="premiumCalculator.gpsIncluded">
    </div>
    <div>
        <p>Czy dzielić składkę na raty</p>
        <select name="divide_in_installments" v-model="premiumCalculator.divideInInstallments">
            <option value="1">Tak</option>
            <option value="0">Nie</option>
        </select>
        <template v-if="premiumCalculator.divideInInstallments == 1">
            <p>Na ile rat rozłożyć</p>
            <select name="rates_count" v-model="premiumCalculator.countOfInstallments">
                <option value="2">2 raty</option>
                <option value="4">4 raty</option>
            </select>
        </template>
    </div>
    <template v-if="premiumCalculator.netPrice > 400000">
        Nie obliczamy
    </template>
    <template v-else>
        <button>OBLICZ</button>
    </template>
    <template>
        WYNIK: {{ ocAcPrice }}
    </template>
  </form>
</template>

<script>
import axios from 'axios';

export default {
    name: 'HelloVue',
    data() {
        return {
            ocAcPrice: '',
            vat: 23,
            premiumCalculator: {
                netPrice: '',
                grossPrice: '',
                yearProduction: '',
                gpsIncluded: true,
                divideInInstallments: 0,
                countOfInstallments: 2
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
        // Calculate dynamiclly Netto price (from gross value)
        calculateNetto() {
            axios.post('/api/calculate-gross-to-net', this.premiumCalculator)
                .then((data) => {
                    this.premiumCalculator.netPrice = data.data;
                });
        },
        // Calculate dynamiclly Gross price (from Net value)
        calculateGross() {
            axios.post('/api/calculate-net-to-gross', this.premiumCalculator)
                .then((data) => {
                    this.premiumCalculator.grossPrice = data.data;
                });
        },
        // Final AC / OC Calculations
        calculateRates() {
            axios.post('/api/calculate-ac-oc', this.premiumCalculator)
                .then((data) => {
                    console.log(data.data)
                })
        }
    }
}
</script>