<?php

// based on code from
// http://codecaine.co.za/posts/compound-elements-with-zend-form

class Custom_View_Helper_FormPeriodLength extends Zend_View_Helper_FormElement {

    public function formPeriodLength($name, $value = null, $attribs = null) {
        // separate value into day, month and year
        $month = '';
        $year = '';
        if (is_array($value)) {
            $month = $value['lengthMonth'];
            $year = $value['lengthYear'];
        } elseif ($value) {
            $month  = ($value % 12);
            $year   = (int) ($value / 12);
        }

        if ($year == 0) {
            $year = '';
        }

        // build select options
        $monthAttribs = isset($attribs['lengthMonthAttribs']) ? $attribs['lengthMonthAttribs'] : array();
        $yearAttribs = isset($attribs['lengthYearAttribs']) ? $attribs['lengthYearAttribs'] : array();

        /**
         * create an array for multiselect
         */
        $multiOptionsMonths = array('-' => 'Months','0'=>'00');
        $multiOptionsYear = array('-' => 'Years', '0' => '00');
        for ($i = 1; $i < 12; $i++) {
            $multiOptions[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $multiOptionsMonths = array_merge($multiOptionsMonths, $multiOptions);
        $multiOptionsYear = array_merge($multiOptionsYear, $multiOptions);

        // return the 2 selects separated by a ' '
        return
                $this->view->formSelect(
                        $name . '[lengthYear]', $year, $yearAttribs, $multiOptionsYear) . ' ' .
                $this->view->formSelect(
                        $name . '[lengthMonth]', $month, $monthAttribs, $multiOptionsMonths)
        ;
    }

}