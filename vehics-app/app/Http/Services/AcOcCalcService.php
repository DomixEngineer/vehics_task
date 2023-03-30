<?php

namespace App\Http\Services;

class AcOcCalcService {

    /**
     * Net to gross calculations
     */
    public function calculateNetToGross($netPrice, $vat) {
        $grossValue = $netPrice / (1 + ($vat / 100));
        $grossValue = round($grossValue, 2);
        return $grossValue;
    }

    /**
     * Gross to Netto calculations
     */
    function calculateGrossToNet($grossPrice, $vat) {
        $netValue = $grossPrice / (1 + ($vat / 100));
        $netValue = round($netValue, 2);
        return $netValue;
    }

    // Count ratio value of a car
    public function countRatioValue($carNetValue)
    {
        if ($carNetValue > 400000) {
            return false;
        }
        if ($carNetValue < 40000) {
            return 8;   // percentage value
        } elseif ($carNetValue >= 40000 && $carNetValue < 100000) {
            return 5; // percentage value
        } elseif ($carNetValue >= 100000 && $carNetValue < 200000) {
            return 4; // percentage value
        } elseif ($carNetValue >= 200000 && $carNetValue < 400000) {
            return 2; // percentage value
        }
    }

    public function incrementRatioIfCarIsOld($ratioValue, $carYearProduction) {
        if ($carYearProduction <= 2021) {
            $ratioValue += 1;
        }
        return $ratioValue;
    }

    public function incrementRadioIfGpsIncluded($ratioValue, $gpsIncluded) {
        if ($gpsIncluded) {
            $ratioValue *= 1.11;
        }
        return $ratioValue;
    }

    public function countAdditionalRatioIfInstallments($carCountOfInstallments, $contributionPrice, $installmentsPrice, $mode) {
        if ($carCountOfInstallments == 2) {
            $contributionPrice *= 1.02;
            $installmentsPrice = 200;
        } elseif ($carCountOfInstallments == 4) {
            $contributionPrice *= 1.04;
            $installmentsPrice = 200;
        } else {
            $installmentsPrice = 0;
        }
        if ($mode == 'installment_pricing') {
            return $installmentsPrice;
        }
        if ($mode == 'contribution_pricing') {
            return $contributionPrice;
        }
        return true;
    }

}

?>