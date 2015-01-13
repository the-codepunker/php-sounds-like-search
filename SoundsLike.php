<?php 
  class SoundsLike
  {

    private $searchAgainst = array();
    private $input;

    /**
    *@param $searchAgainst - an array of strings to match agains $input
    *@param $input - the string for which the class finds the closest match in $searchAgainst
    */
    public function __construct($searchAgainst, $input)
    {
      $this->searchAgainst = $searchAgainst;
      $this->input = $input;
    }

    /**
    *@param $phrase - string
    *@return an array of metaphones for each word in a string
    */
    private function getMetaPhone($phrase)
    {
      $metaphones = array();
      $words = str_word_count($phrase, 1);
      foreach ($words as $word) {
        $metaphones[] = metaphone($word);
      }
      return $metaphones;
    }

    /**
    *@return the closest matching string found in $this->searchAgainst when compared to $this->input
    */
    public function findBestMatch()
    {
      $foundbestmatch = -1;

      //get the metaphone equivalent for the input phrase
      $tempInput = implode(' ', $this->getMetaPhone($this->input));

      foreach ($this->searchAgainst as $phrase)
      {
        //get the metaphone equivalent for each phrase we're searching against
        $tempSearchAgainst = implode(' ', $this->getMetaPhone($phrase));
        $similarity = levenshtein($tempInput, $tempSearchAgainst);

        if ($similarity == 0) // we found an exact match
        {
          $closest = $phrase;
          $foundbestmatch = 0;
          break;
        }

        if ($similarity <= $foundbestmatch || $foundbestmatch < 0)
        {
          $closest  = $phrase;
          $foundbestmatch = $similarity;
        }
      }

      return $closest;
    }

  }

  $input = "The quick brown fox jumped over the lazy dog";
  $searchAgainst = array("The quick brown cat jumped over the lazy dog", "Thors hammer jumped over the lazy dog", "The quick brown fax jumped over the lazy dog");

  $SoundsLike = new SoundsLike($searchAgainst, $input);
  echo $SoundsLike->findBestMatch();
