<?php 
use yii\helpers\Html as HelperHtml ;

class Html extends HelperHtml
{
   protected static function activeBooleanInput($type, $model, $attribute, $options = [])
   {
       $name = isset($options['name']) ? $options['name'] : static::getInputName($model, $attribute);
       $value = static::getAttributeValue($model, $attribute);

       if (!array_key_exists('value', $options)) {
           $options['value'] = '1';
       }
       if (!array_key_exists('uncheck', $options)) {
           $options['uncheck'] = '0';
       } elseif ($options['uncheck'] === false) {
           unset($options['uncheck']);
       }
       if (!array_key_exists('label', $options)) {
           $options['label'] = static::encode($model->getAttributeLabel(static::getAttributeName($attribute)));
       } elseif ($options['label'] === false) {
           unset($options['label']);
       }

       $checked = "$value" === "{$options['value']}";

       if (!array_key_exists('id', $options)) {
           $options['id'] = static::getInputId($model, $attribute);
       }

       return parent::$type($name, $checked, $options);
   }
}
?>