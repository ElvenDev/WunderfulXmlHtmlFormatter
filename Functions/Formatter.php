<?php
/**
 * Created by PhpStorm.
 * User: madis
 * Date: 16/03/2018
 * Time: 12:29
 */

namespace Functions;


/**
 * Formats xml / html file into correct string formatting.
 * Class Formatter
 * @package Functions
 * @author Madis Ploompuu <madisp@gmail.com>
 */
class Formatter
{
    protected $string = "";
    protected $tabCounter = 0;

    /**
     * Set string content from variable.
     * @param $string
     * @return $this
     */
    public function setString($string) {
        $this->string = $string;

        return $this;
    }

    /**
     * Set string content depending from uploaded file.
     * @param $files
     * @return $this
     */
    public function setFiles($files) {
        $this->string = file_get_contents($files['fileToUpload']['tmp_name']);

        return $this;
    }

    /**
     * Formats $this->string variable.
     * @return $this
     */
    public function format() {
        if($this->string == "") {
            return $this;
        }

        $newString = "";

        $this->stripWhitespaces();

        $explodedSmallerThan = explode("<", $this->string);

        foreach ($explodedSmallerThan as $exploded) {
            if ($exploded == "") continue;

            $newString .= $this->createStringRowFromArray($exploded);
        }

        $this->string = $newString;

        return $this;
    }

    /**
     * Depending from $exploded variable, returns one or two rows of string.
     * @param $exploded
     * @return string
     */
    protected function createStringRowFromArray($exploded) {
        $string = "";
        list($tag, $afterTagContent) = explode(">", $exploded);

        if (substr($tag, -1) == "/") {
            $string .= $this->getTabs(0);
        } else if(substr($tag, 0, 1) == "/") {
            $string .= $this->getTabs(-1);
        } else {
            $string .= $this->getTabs(1);
        }
        $string .= "<" . $tag . ">\n";

        if (isset($afterTagContent) && $afterTagContent != "" && $afterTagContent != " ") {
            $string .= $this->getTabs() . $afterTagContent . "\n";
        }

        return $string;
    }

    /**
     * Replace all unnecessary whitespace characters, replacing it with a single space. (Makes into 1 line)
     */
    protected function stripWhitespaces()
    {
        $this->string = preg_replace('/\s+/', ' ', $this->string);

        return $this;
    }

    /**
     * Returns amount of tabs depending of counter.
     * @param int $change - Adds that much into counter. If negative, adds before getting tabs, if positive then after.
     * @return string - Returns tabs, consisting of "\t"
     */
    protected function getTabs($change = 0) {
        if($change < 0) {
            $this->tabCounter = $this->tabCounter + $change;
        }

        $string = $this->generateTabs();

        if($change > 0) {
            $this->tabCounter = $this->tabCounter + $change;
        }

        return $string;
    }

    /**
     * get Tabs alone, depending from tabCounter.
     * @return string
     */
    protected function generateTabs() {
        $string = "";
        for ($i = 0; $i < $this->tabCounter; $i++) {
            $string .= "\t";
        }

        return $string;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}