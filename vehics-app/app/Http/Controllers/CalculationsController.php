<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculationsController extends Controller {

    protected $netValue;
    protected $grossValue;
    protected $vat = 23;
    protected $carYear;

    /**
     * Test method to testing API's
     */
    public function testMethod() {
        return 'Hello world !';
    }

    /**
     * Netto to brutto calculations
     */
    public function calculateNetToGross(Request $request) {
        $grossValue = $request->input('netPrice') / (1 + ($this->vat / 100));
        $this->grossValue = round($grossValue, 2);
        return $this->grossValue;
    }

    /**
     * Brutto to Netto calculations
     */
    function calculateGrossToNet(Request $request) {
        $netValue = $request->input('grossPrice') / (1 + ($this->vat / 100));
        $this->netValue = round($netValue, 2);
        return $this->netValue;
    }

    public function calculateAcOc(Request $request) {

        $carNetValue = $request->input('netPrice');
        $carGrossValue = $request->input('grossPrice');
        $carYearProduction = $request->input('yearProduction');
        $carGpsIncluded = $request->input('gpsIncluded');
        $carDivideInInstallments = $request->input('divideInInstallments');
        $carCountOfInstallments = 0;
        $ratioValue = 0;
        if ($carDivideInInstallments == 1) {
            $carCountOfInstallments = $request->input('countOfInstallments');
        }

        // Secure car over pricing
        if ($carNetValue > 400000) {
            return "Uwaga: samochód jest zbyt drogi, nie można wyliczyć składki."; 
        }

        // Set the proper car ratio value
        if ($carNetValue < 40000) {
            $ratioValue = 8;   // percentage value
        } elseif ($carNetValue >= 40000 && $carNetValue < 100000) {
            $ratioValue = 5; // percentage value
        } elseif ($carNetValue >= 100000 && $carNetValue < 200000) {
            $ratioValue = 4; // percentage value
        } elseif ($carNetValue >= 200000 && $carNetValue < 400000) {
            $ratioValue = 2; // percentage value
        }
        
        // Add ratio if car is used
        if ($carYearProduction <= 2021) {
            $ratioValue += 1;
        }

        // Increment ratio if GPS has to be included
        if ($carGpsIncluded) {
            $ratioValue *= 1.11;
        }

        // contribution price
        $contributionPrice = round($carNetValue * $ratioValue / 100, 2);

        // Count additional ratio value if rates are supposed to be
        if ($carCountOfInstallments == 2) {
            $contributionPrice *= 1.02;
            $ratingPricing = 200;
        } elseif ($carCountOfInstallments == 4) {
            $contributionPrice *= 1.04;
            $ratingPricing = 200;
        } else {
            $ratingPricing = 0;
        }

        // Count the price of contribution rate
        if ($carCountOfInstallments > 1) {
            $rateOfContributionPrice = round(($contributionPrice + $ratingPricing) / $carCountOfInstallments, 2);
        } else {
            $rateOfContributionPrice = $contributionPrice;
        }

        $results = [
            'net_price' => $carNetValue,                                // Cena netto
            'gross_price' => $carGrossValue,                            // Cena brutto
            'year_of_production' => $carYearProduction,                 // Rok produkcji
            'gps_included' => $carGpsIncluded,                          // Czy GPS ma być w samochodzie
            'ratio_value' => $ratioValue,                               // Współczynnik
            'contribution_price' => $contributionPrice,                 // Składka
            'rating_pricing' => $ratingPricing,                         // Opłaty ratowe
            'count_of_installments' => $carCountOfInstallments,         // Liczba rat
            'rate_of_contribution_price' => $rateOfContributionPrice    // Rata składki
        ];

        return $results;
    }

}