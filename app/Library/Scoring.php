<?php

namespace App\Library;


class Scoring
{
    private $wrong_answers;
    function __construct($wrong_answers)
    {
        $this->wrong_answers = $wrong_answers;
    }


    function get_wrong_answers()
    {
        return $this->wrong_answers;
    }

    function get_performance()
    {

        switch (true) {
            case in_array($this->wrong_answers, range(0, 0)):
                return ['E', "Excellent"];
                break;
            case in_array($this->wrong_answers, range(1, 1)):
                return ['HS', "Highly Satisfactory"];
                break;
            case in_array($this->wrong_answers, range(2, 2)):
                return ['A', "Acceptable"];
                break;
            default:
                return ['F', "Failed"];
                break;
        }
    }
}
