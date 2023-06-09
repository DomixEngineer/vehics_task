<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AcOcCalcService;
use App\Http\Requests\CalculateAcOcRequest;

class CalculationsController extends Controller {

    protected $vat = 23;

    /**
     * Test method to testing API's
     */
    public function testMethod() {
        return 'Hello world !';
    }

    /**
     * Netto to brutto calculations
     */
    public function calculateNetToGross(Request $request, AcOcCalcService $acOcCalcService) {
        return $acOcCalcService->calculateNetToGross($request->input('netPrice'), $this->vat);
    }

    /**
     * Brutto to Netto calculations
     */
    function calculateGrossToNet(Request $request, AcOcCalcService $acOcCalcService) {
        return $acOcCalcService->calculateGrossToNet($request->input('grossPrice'), $this->vat);
    }

    public function calculateAcOc(CalculateAcOcRequest $request, AcOcCalcService $acOcCalcService) {

        $results = [];
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

        $ratioValue = $acOcCalcService->countRatioValue($carNetValue);
        if (!$ratioValue) {
            $results = [
                'msg' => 'Samochód jest zbyt drogi, nie można wyliczyć składki.'
            ];
            return $results;
        }
        
        // Add ratio if car is used
        $ratioValue = $acOcCalcService->incrementRatioIfCarIsOld($ratioValue, $carYearProduction);

        // Increment ratio if GPS has to be included
        $ratioValue = $acOcCalcService->incrementRadioIfGpsIncluded($ratioValue, $carGpsIncluded);

        // contribution price
        $contributionPrice = round($carNetValue * $ratioValue / 100, 2);

        // Rating pricing
        $installmentsPricing = 0;

        // Count additional ratio value if installments are supposed to be
        $installmentsPricing = $acOcCalcService->countAdditionalRatioIfInstallments(
            $carCountOfInstallments,
            $contributionPrice,
            $installmentsPricing,
            'installment_pricing'
        );

        $contributionPrice = $acOcCalcService->countAdditionalRatioIfInstallments(
            $carCountOfInstallments,
            $contributionPrice,
            $installmentsPricing,
            'contribution_pricing'
        );

        // Count the price of contribution rate
        if ($carCountOfInstallments > 1) {
            $rateOfContributionPrice = round(($contributionPrice + $installmentsPricing) / $carCountOfInstallments, 2);
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
            'installments_pricing' => $installmentsPricing,             // Opłaty ratowe
            'count_of_installments' => $carCountOfInstallments,         // Liczba rat
            'rate_of_contribution_price' => $rateOfContributionPrice    // Rata składki
        ];

        return $results;
    }

}