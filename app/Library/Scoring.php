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
            case in_array($this->wrong_answers, range(0, 2)):
                return ['E', "Excellent"];
                break;
            case in_array($this->wrong_answers, range(3, 5)):
                return ['HS', "Highly Satisfactory"];
                break;
            case in_array($this->wrong_answers, range(6, 8)):
                return ['A', "Acceptable"];
                break;
            default:
                return ['F', "Failed"];
                break;
        }
    }
}
