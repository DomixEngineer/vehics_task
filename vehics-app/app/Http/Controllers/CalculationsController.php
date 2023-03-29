<?php

namespace App\Http\Controllers;

class CalculationsController extends Controller {

    /**
     * Test method to testing API's
     */
    public function testMethod() {
        return 'Hello world !';
    }

    /**
     * Netto to brutto calculations
     */
    public function NetToGross($netto, $vat = 23) {
        $brutto = $netto * (1 + ($vat / 100));
        return $brutto;
    }

    /**
     * Brutto to Netto calculations
     */
    function GrossToNet($brutto, $vat = 23) {
        $netto = $brutto / (1 + ($vat / 100));
        return $netto;
    }

    public function calculateAcOc(Request $request) {
        
        $carValue = $request->input('car_value');
        $carYear = $request->input('car_year');
        $gps = $request->input('gps', true);
        $installments = $request->input('installments', 1);
        $nettoValue = $request->input('netto_value');


    }

}