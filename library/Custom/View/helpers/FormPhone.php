<?php

// based on code from
// http://codecaine.co.za/posts/compound-elements-with-zend-form

class Custom_View_Helper_FormPhone extends Zend_View_Helper_FormElement
{
    public function formPhone ($name, $value = null, $attribs = null)
    {
        // separate value into day, month and year
        $phone1 = '';
        $phone2 = '';
        $phone3 = '';
        if (is_array($value)) {
            $phone1 = $value['first'];
            $phone2 = $value['second'];
            $phone3 = $value['third'];
        } elseif ($value) {
            list($phone1, $phone2, $phone3) = explode('-', $value);
        }

        $phone1Attribs = isset($attribs['phone1Attribs']) ? $attribs['phone1Attribs'] : array();
        $phone2Attribs = isset($attribs['phone2Attribs']) ? $attribs['phone2Attribs'] : array();
        $phone3Attribs = isset($attribs['phone3Attribs']) ? $attribs['phone3Attribs'] : array();

        $default1Attribs = array(
                                'size' => 3,
                                'maxlength' => 3,
                                'id' => 'phone1',
                                'class' => 'length3',
                                'composite' => 'phone'
                                );
        $default2Attribs = array(
                                'size' => 3,
                                'maxlength' => 3,
                                'id' => 'phone2',
                                'class' => 'length3',
                                'composite' => 'phone'
                                );
        $default3Attribs = array(
                                'size' => 4,
                                'maxlength' => 4,
                                'id' => 'phone3',
                                'class' => 'length4',
                                'composite' => 'phone'
                                );
        $phone1Attribs = array_merge($default1Attribs, $phone1Attribs);
        $phone2Attribs = array_merge($default2Attribs, $phone2Attribs);
        $phone3Attribs = array_merge($default3Attribs, $phone3Attribs);

        // return the 3 selects separated by
           return  $this->view->formText($name.'[first]', $phone1, $phone1Attribs).
                        '<span>-</span>'.
                            $this->view->formText($name.'[second]', $phone2, $phone2Attribs).
                        '<span>-</span>'.
                            $this->view->formText($name.'[third]', $phone3, $phone3Attribs);
    }
}