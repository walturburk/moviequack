<?php

class Template { //Class for processing html-template
  protected $file;
  protected $values = array();

  public function __construct($file) {
    $this->file = $file;
    $this->html = file_get_contents($file);
    /*if ($element != null) {
      $this->html = $this->getElement($element);
    }*/
  }

  public function set($key, $value) {
    $this->values[$key] = $value;
  }

  public function getVariables($filecontent) {
    $pattern = "/{(.*?)}/";
    preg_match_all($pattern, $filecontent, $matches);
    return $matches;
  }

  public function setVariables($matches) {
    foreach ($matches[0] AS $match) {
      $match = str_replace("{", "", $match);
      $match = str_replace("}", "", $match);
      global $$match;
      if (isset($$match)) {
        $this->set($match, $$match);
      }
    }
  }

  public function grabElement($identifier) {
    $regex = '/<([A-Z][A-Z0-9]*)[A-Z0-9"\'-_{} ]*'.$identifier.'[A-Z0-9"\'-_{} ]*>.*?<\/\1>/si';
    preg_match($regex, $this->html, $match);
    $this->html = preg_replace($regex, "", $this->html);
    return $match[0];
  }


  public function output($output = null) {
    if ($output == null) {
      $output = $this->html;
    }
    $variables = $this->getVariables($output);
    $this->setVariables($variables);

    foreach ($this->values as $key => $value) {
      $tagToReplace = "{{$key}}";
      $output = str_replace($tagToReplace, $value, $output);
    }

    return $output;
  }
}

?>
