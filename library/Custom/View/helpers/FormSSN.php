<?php

// based on code from
// http://codecaine.co.za/posts/compound-elements-with-zend-form

class Custom_View_Helper_FormSSN extends Zend_View_Helper_FormElement
{
    public function formSSN ($name, $value = null, $attribs = null)
    {
        // separate value into day, month and year
        $ssn1 = '';
        $ssn2 = '';
        $ssn3 = '';
        if (is_array($value)) {
            $ssn1 = $value['first'];
            $ssn2 = $value['second'];
            $ssn3 = $value['third'];
        } elseif ($value) {
            list($ssn1, $ssn2, $ssn3) = explode('-', $value);
        }

        $dayAttribs = isset($attribs['ssn1Attribs']) ? $attribs['ssn1Attribs'] : array();
        $monthAttribs = isset($attribs['ssn2Attribs']) ? $attribs['ssn2Attribs'] : array();
        $yearAttribs = isset($attribs['ssn3Attribs']) ? $attribs['ssn3Attribs'] : array();

        // return the 3 selects separated by
           return  $this->view->formText($name.'[first]', $ssn1, array(
                                'size' => 3,
                                'maxlength' => 3,
                                'required' => True,
                                'id' => 'ssn1',
                                'class' => 'length3',
                                'composite' => 'ssn'
                                )).
                        '<span>-</span>'.
                            $this->view->formText($name.'[second]', $ssn2, array(
                                'size' => 2,
                                'maxlength' => 2,
                                'required' => True,
                                'id' => 'ssn2',
                                'class' => 'length2',
                                'composite' => 'ssn'
                                )).
                        '<span>-</span>'.
                            $this->view->formText($name.'[third]', $ssn3, array(
                                'size' => 4,
                                'maxlength' => 4,
                                'required' => True,
                                'id' => 'ssn3',
                                'class' => 'length4',
                                'composite' => 'ssn'
                                ));
    }
}