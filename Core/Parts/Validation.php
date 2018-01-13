<?php

namespace Core\Parts;
use Core\Parts\Model;

class Validation 
{
  private $_passed = false,
           $_errors = array(),
           $_db     =  null;
  public function __construct()
  {
    // $this->_db = Model::getDb();
  }
  public function validate($source, $items=array())
  {
    foreach($items as $item => $rules)
    {
      $newItem = preg_replace("/[^a-zA-Z]/", "\t", $item);
      foreach($rules as $rule =>$rule_value)
      {
          $value = trim($source[$item]);
        // echo"{$item} {$rule} must be {$rule_value}<br />";
        if($rule ==='required' && empty($value))
        {
          $this->addError("{$newItem} is required");
        }else if(!empty($value))
        {
          switch($rule)
          {

            case 'min':
              if(strlen($value)< $rule_value){
                $this->addError("<span style='color:red;'>{$newItem} must be minimum of {$rule_value} characters.</span>");
              }
             break;
             case 'max':
             if(strlen($value) > $rule_value){
               $this->addError("<span style='color:red;'>{$nwItem} must be maximum of {$rule_value} characters.</span>");
             }
             break;
             case 'matches':
              if($value != $source[$rule_value])
              {
                $this->addError("<span style='color:red;'>{$item} doesn't match {$rule_value} </span>");
              }
             break;
             case 'unique':
             $check = Model::getDb()->get($rule_value, array($item,'=',$value));
             if($check->count())
             {
               $this->addError("<span style='color:red;'>{$item} already exists!</span>");
             }
             break;

          }
        }
      }
    }
    //check if the error is empty and set the _passed to true
    if(empty($this->_errors))
    {
      $this->_passed = true;
    }
    return $this;

  }

  private function addError($error)
  {
    $this->_errors[]  = $error;
  }
  public function errors()
  {
    return $this->_errors;
  }
  public function isPassed()
  {
    return $this->_passed;
  }
  public function isNotFailed()
  {
    return $this->_passed = true;
  }

}
