<?
class Custom_View_Helper_ErrorView extends Zend_View_Helper_FormElement
{
    public function errorView($errors, array $options = null)
    {

        if (empty($options['class'])) {
            $options['class'] = 'errors';
        }

        $start = "<span%s>";
        $end = "</br></span>";
        if (strstr($start, '%s')) {
            $start   = sprintf($start, " class='{$options['class']}'");
        }

        $html  = $start
               . array_pop($errors)
               . $end;

        return $html;
    }
}
