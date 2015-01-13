<?php 
  class SoundsLike
  {

    private $searchAgainst = array();
    private $input;

    public function __construct($searchAgainst, $input)
    {
      $this->searchAgainst = $searchAgainst;
      $this->input = $input;
    }

    private function getMetaPhone($phrase)
    {
      $metaphones = array();
      $words = str_word_count($phrase, 1);
      foreach ($words as $word) {
        $metaphones[] = metaphone($word);
      }
      return $metaphones;
    }

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
  
