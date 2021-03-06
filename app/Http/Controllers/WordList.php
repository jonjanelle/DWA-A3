<?php
  namespace App\Http\Controllers;
  /*
    Class to maintain and manipulate a list of words loaded from a data file.
    author: Jon Janelle
    email: jonjanelle1@gmail.com
    Created: 3/11/2017
  */
  class WordList{
    private $wordList;
    private $length;

    /*
    // Construct a new WordArray
    // $fileName : A string file path for the plain text data file.
    //             Each line is assumed to contain one word.
    */
    function __construct($fileName) {
      //Figuring out how to make the next two lines work took WAY too long...
      $path = storage_path('app/public/'.$fileName);
      $fh=fopen($path, "r") or die("Unable to open file.");
      $this->wordList =array();
      while (!feof($fh)) {
        $this->wordList[] = trim(fgets($fh));
        array_push($this->wordList,trim(fgets($fh))); //add all words to array
      }

      //$this->wordList =array("blah","blah","blah");
      //length is total number of words in wordList
      $this->length = count($this->wordList);
    }

    /*
    // Create a paragraph of text from words in $wordList.
    // Returns a string containing the paragraph.
    // $spp  : int average sentences per paragraph
    // $devP : int max absolute deviation from average sentences per paragraph
    // $sLen : int average number of words per sentence
    // $devS : int max absolute deviation from words per sentence
    */
    function getParagraph($spp, $devP, $sLen, $devS, $headers, $punct) {
      if ($headers) {
        $paragraph = array();
        $head ="";
        $head_len = rand(1,4); //paragraph header between 1 and 4 words.
        for ($m=0; $m < $head_len; $m++){
          if ($m==0) {
            $head.=ucfirst($this->wordList[array_rand($this->wordList)])." ";
          }
          else {
              $head.=$this->wordList[array_rand($this->wordList)]." ";
          }
        }
        $paragraph['header'] = $head;
      }

      $body = "";
      $spp=$spp + rand(-$devP, $devP);
      for ($j=0; $j<$spp; $j++) { //sentences per paragraph loop
        $body.=$this->getSentence($sLen, $devS, $punct);
      }
      $paragraph['body'] = $body;
      return $paragraph;
    }

    /**
    * Generate one sentence of text from wordList
    * $sLen : int average number of words per sentence
    * $devS : int max absolute deviation from words per sentence
    */
    function getSentence($sLen, $devS, $punct){
      $sentence = "";
      //generate new length for next sentence within deviation bounds
      $sLen=$sLen + rand(-$devS,$devS);
      for ($k=0; $k<$sLen-1; $k++) { //words per sentence loop
        if ($k==0){ //Make first word of each sentence uppercase
          $sentence.=ucfirst($this->wordList[rand(0,$this->length-1)])." ";
        }
        else {
          $sentence.=$this->wordList[rand(0,$this->length-1)]." ";
        }
      }
      //add last word and end each sentence with a period.
      $endPunct = $punct[array_rand($punct)]." ";
      $sentence.=$this->wordList[rand(0,$this->length-1)].$endPunct;
      return $sentence;
    }

  }
