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
    protected $string;
    protected $tabCounter = 0;

    /**
     * Formatter constructor.
     * @param $files
     */
    public function __construct($files)
    {
        $this->string = file_get_contents($files['fileToUpload']['tmp_name']);
    }

    /**
     * Replace all unnecessary whitespace characters, replacing it with a single space. (Makes into 1 line)
     */
    public function stripWhitespaces()
    {
        $this->string = preg_replace('/\s+/', ' ', $this->string);
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
        $string = "";
        for ($i = 0; $i < $this->tabCounter; $i++) {
            $string .= "\t";
        }

        if($change > 0) {
            $this->tabCounter = $this->tabCounter + $change;
        }

        return $string;
    }

    /**
     * Formats $this->string variable.
     */
    public function format() {
        $newString = "";

        $this->stripWhitespaces();

        $explodedSmallerThan = explode("<", $this->string);

        foreach ($explodedSmallerThan as $exploded) {
            if ($exploded == "") continue;

            $explodedFinal = explode(">", $exploded);

            if (substr($explodedFinal[0], -1) == "/") {
                $newString .= $this->getTabs(0);
            } else if(substr($explodedFinal[0], 0, 1) == "/") {
                $newString .= $this->getTabs(-1);
            } else {
                $newString .= $this->getTabs(1);
            }
            $newString .= "<" . $explodedFinal[0] . ">\n";

            if (isset($explodedFinal[1]) && $explodedFinal[1] != "" && $explodedFinal[1] != " ") {
                $newString .= $this->getTabs(0) . $explodedFinal[1] . "\n";
            }
        }

        $this->string = $newString;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}