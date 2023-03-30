<template>
    <div>
        <div class="app-nav">
            <div class="app-nav__brand">
                <img src="/svg/logo.svg">
            </div>
            <div class="app-nav__title">
                <p>kalkulator składek oc/ac</p>
            </div>
        </div>
        <div class="calc-app-section">
            <h1 class="calc-app-section__header">
                Projekt oraz realizacja:<br>Dominik Dziewulski
            </h1>
        </div>
        <div class="app-main">
            <h1 class="app-main__header">Kalkulator składek OC / AC</h1>
            <form 
                @submit.prevent="calculateRates()" 
                class="app-main__form">
                <div class="app-main__form__row">
                    <label class="app-main__form__row__label" for="net_price">Cena netto</label>
                    <input 
                        type="number" 
                        class="app-main__form__row__input" 
                        name="net_price"
                        id="net_price"
                        step='0.01'
                        v-model="premiumCalculator.netPrice"
                        @change="calculateGross()"
                    >
                    <div class="app-main__form__row__error" v-if="premiumCalculator.netPrice > 400000">
                        <p class="app-main__form__row__error__text">Nie obliczamy składek dla samochodów droższych niż 400000zł netto</p>
                    </div>
                    <div class="app-main__form__row__error" v-if="formErrors && formErrors.netPrice">
                        <p class="app-main__form__row__error__text">{{ formErrors.netPrice[0] }}</p>
                    </div>
                </div>
                <div class="app-main__form__row">
                    <label class="app-main__form__row__label" for="gross_price">Cena brutto</label>
                    <input 
                        type="number" 
                        class="app-main__form__row__input" 
                        name="gross_price"
                        id="gross_price"
                        step='0.01'
                        v-model="premiumCalculator.grossPrice"
                        @change="calculateNetto()"
                    >
                    <div class="app-main__form__row__error" v-if="formErrors && formErrors.grossPrice">
                        <p class="app-main__form__row__error__text">{{ formErrors.grossPrice[0] }}</p>
                    </div>
                </div>
                <div class="app-main__form__row">
                    <label class="app-main__form__row__label" for="year_production">Rok produkcji samochodu</label>
                    <input 
                        type="number" 
                        class="app-main__form__row__input"
                        name="year_production"
                        id="year_production"
                        v-model="premiumCalculator.yearProduction"
                    >
                    <div class="app-main__form__row__error" v-if="formErrors && formErrors.yearProduction">
                        <p class="app-main__form__row__error__text">{{ formErrors.yearProduction[0] }}</p>
                    </div>
                </div>
                <div class="app-main__form__row">
                    <label 
                        class="app-main__form__row__label"
                    >
                        <input 
                            type="checkbox" 
                            v-model="premiumCalculator.gpsIncluded"
                        />Czy GPS ma być zawarty
                    </label>
                </div>
                <div class="app-main__form__row">
                    <label 
                        class="app-main__form__row__label"
                    >
                        <input 
                            type="checkbox" 
                            v-model="premiumCalculator.divideInInstallments"
                        />Czy dzielić składkę na raty
                    </label>
                </div>
                <div class="app-main__form__row" v-if="premiumCalculator.divideInInstallments">
                    <label class="app-main__form__row__label" for="installments_count">Ilość rat</label>
                    <select 
                        name="rates_count" 
                        v-model="premiumCalculator.countOfInstallments"
                        class="app-main__form__row__input"
                    >
                        <option value="2">2 raty</option>
                        <option value="4">4 raty</option>
                    </select>
                </div>
                <div class="app-main__form__row">
                    <button 
                        class="app-main__form__row__btn"
                        :disabled="premiumCalculator.netPrice > 400000"
                        :class="{ 'app-main__form__row__btn--disabled' : premiumCalculator.netPrice > 400000 }">
                        Oblicz składkę
                        </button>
                </div>
            </form>
            <template v-if="isCalculated">
                <div class="app-main__decorator"></div>
                <div class="app-main__results">
                    <h1 class="app-main__results__header">Wyniki obliczeń składki OC/AC</h1>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Cena netto</div>
                        <div class="app-main__results__row__result">{{ calculatedData.net_price }} zł</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Cena brutto</div>
                        <div class="app-main__results__row__result">{{ calculatedData.gross_price }} zł</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Rok produkcji</div>
                        <div class="app-main__results__row__result">{{ calculatedData.year_of_production }}</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Montaż GPS</div>
                        <div class="app-main__results__row__result">{{ calculatedData.gps_included ? 'Tak' : 'Nie' }}</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Współczynnik</div>
                        <div class="app-main__results__row__result">{{ calculatedData.ratio_value }} %</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Składka</div>
                        <div class="app-main__results__row__result">{{ calculatedData.contribution_price }} zł</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Opłaty ratowe</div>
                        <div class="app-main__results__row__result">{{ calculatedData.installments_pricing }} zł</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Liczba rat</div>
                        <div class="app-main__results__row__result">{{ calculatedData.count_of_installments }}</div>
                    </div>
                    <div class="app-main__results__row">
                        <div class="app-main__results__row__label">Rata składki</div>
                        <div class="app-main__results__row__result">{{ calculatedData.rate_of_contribution_price }} zł</div>
                    </div>
                </div>
            </template>
        </div>
    </div>
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
                divideInInstallments: false,
                countOfInstallments: 2
            },
            isCalculated: false,
            calculatedData: {},
            formErrors: {}
        }
    },
    methods: {
        testMethod() {
            axios.post('/api/test')
                .then((data) => {
                    console.log(data.data)
                })
        },
        clearErrors() {
            this.formErrors = {}
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
            this.clearErrors();
            axios.post('/api/calculate-ac-oc', this.premiumCalculator)
                .then((data) => {
                    this.calculatedData = data.data
                    this.isCalculated = true
                    console.log(this.calculatedData)
                })
                .catch((errors) => {
                    this.formErrors = errors.response.data.errors;
                })
        }
    }
}
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700&display=swap');

html, body {
    padding: 0;
    margin: 0;
    overflow-x: hidden;
}

.app-nav {
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    left: 0;
    right: 0;
    margin-top: 2vh;
    z-index: 999;
    &__title {
        p {
            color: white;
            text-transform: uppercase;
            font-family: 'inter';
            font-weight: 700;
            font-size: 19px;
        }
    }
}

.calc-app-section {
    height: 100vh;
    width: 100vw;
    background-image: url('../../images/BG.png');
    background-size: cover;
    position: relative;
    &__header {
        color: white;
        position: absolute;
        left: 0;
        right: 0;
        display: block;
        font-family: 'inter';
        text-transform: uppercase;
        font-size: 3vw;
        text-align: center;
        top: 33vh;
    }
}
.app-main {
    &__header {
        font-family: 'inter';
        text-align: center;
        text-transform: uppercase;
    }
    &__form {
        max-width: 600px;
        width: 100vw;
        margin: 0 auto;
        &__row {
            margin-bottom: 4vh;
            &__label {
                display: block;
                font-family: 'inter';
                font-weight: 400;
            }
            &__input {
                width: 100%;
                padding: 12px;
                box-sizing: border-box;
                outline: none;
                margin-top: 10px;
                border: 1px solid #b2bec3;
                border-radius: 7px;
            }
            &__btn {
                border: 2px solid #0984e3;
                background-color: #74b9ff;
                color: white;
                font-family: 'inter';
                box-sizing: border-box;
                padding: 10px 40px;
                border-radius: 5px;
                cursor: pointer;
                &--disabled {
                    border: 2px solid #d63031;
                    background-color: #ff7675;
                }
            }
            &__error {
                border: 2px solid #d63031;
                background-color: #ff7675;
                border-radius: 7px;
                text-align: center;
                padding: 7px;
                box-sizing: border-box;
                margin-top: 10px;
                &__text {
                    color: white;
                    font-family: 'inter';
                    margin: 0;
                    padding: 0;
                }
            }
        }
    }
    &__decorator {
        max-width: 600px;
        width: 100%;
        height: 1px;
        background-color: #b2bec3;
        margin: 0 auto;
    }
    &__results {
        max-width: 700px;
        width: 100%;
        margin: 0 auto;
        &__header {
            font-family: 'inter';
            font-weight: 700;
            text-transform: uppercase;
        }
        &__row {
            display: flex;
            justify-content: space-between;
            max-width: 50%;
            margin: 40px auto;
            border-bottom: 1px solid #636e72;
            padding: 0px 40px 5px 40px;
            &__label {
                font-family: 'inter';
            }
            &__result {
                font-family: 'inter';
                font-weight: 700;
            }
        }
    }
}
</style>