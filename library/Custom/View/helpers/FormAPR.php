<?php

// based on code from
// http://codecaine.co.za/posts/compound-elements-with-zend-form

class Custom_View_Helper_FormAPR extends Zend_View_Helper_FormElement {

    public function formAPR($name, $value = null, $attribs = null) {
        // separate value into day, month and year
        $integer = '';
        $hundreds = '';
        if (is_array($value)) {
            $integer = $value['lengthSecond'];
            $hundreds = $value['lengthFirst'];
        } elseif ($value) {
            $integer  = ($value);                                                                           //year==hundreds
            $hundreds   = (float) ('.'.$value);
        }

        if ($hundreds == 0) {
            $hundreds = '';
        }

        // build select options
        $integerAttribs = isset($attribs['lengthIntegerAttribs']) ? $attribs['lengthIntegerAttribs'] : array();
        $hundredAttribs = isset($attribs['lengthHundredAttribs']) ? $attribs['lengthHundredAttribs'] : array();

        /**
         * create an array for multiselect
         */
        $multiOptionsInteger = array('' => 'Integer');
        $multiOptionsHundred = array('-' => 'Hundred');
        for ($i = 0; $i <= 30; $i++) {
            $multiOptions[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        for ($j = 0; $j <= 99; $j++) {
            $multiOpt[$j] = str_pad('.'.$j, 2, '0', STR_PAD_LEFT);
        }



        $multiOptionsInteger = array_merge($multiOptionsInteger, $multiOptions);
        $multiOptionsHundred = array_merge($multiOptionsHundred, $multiOpt);

        // return the 2 selects separated by a ' '
        return
                $this->view->formSelect(
                        $name . '[lengthSecond]', $integer, $integerAttribs, $multiOptionsInteger) . ' ' .
                $this->view->formSelect(
                        $name . '[lengthFirst]', $hundreds, $hundredAttribs, $multiOptionsHundred)
        ;
    }

}