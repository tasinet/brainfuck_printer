<?php

class BrainFuckPrinter {

    public $out = "";

    public function __construct( $string )
    {
        $this->out = "++++++++++[>++++++++++>+++++++++++>++++++++++++<<<-]>";
        $this->cells = array(100,110,120);
        $this->ptr = 0;

        foreach(str_split($string) as $char) {
            $this->output($char);
        }
    }

    public function output( $char )
    {
        $asc = ord($char);
        list($index, $offset) = $this->findClosest($asc);
        $this->moveTo($index);
        $this->shift($offset);
        $this->spill();
        $this->shift(-$offset);
        $this->moveTo(-$index);
    }

    public function findClosest($asc)
    {
        $minIndex = -1;
        $minOffset = 999;
        foreach($this->cells as $index => $val) {
            $offset = abs($val - $asc);
            if ($minOffset > $offset) {
                $minIndex = $index;
                $minOffset = $offset;
                $minActualOffset = $asc - $val;
            }
        }
        return array($minIndex, $minActualOffset);
    }

    public function moveTo($index)
    {
        $distance = $index - $this->ptr;
        $op = $distance > 0 ? ">" : "<";
        $distance = abs($distance);
        for($i=0;$i<$distance;$i++)
            $this->out .= $op;
    }

    public function shift($offset)
    {
        $op = $offset > 0 ? "+" : "-";
        $offset = abs($offset);
        for($i=0;$i<$offset;$i++)
            $this->out .= $op;
    }

    public function spill()
    {
        $this->out .= ".";
    }

}

$arg = $argv;

array_shift($arg);

$bf = new BrainFuckPrinter( implode(($arg), " ")."\n" );

file_put_contents("php.bf", $bf->out);

shell_exec("./bf < php.bf > phpbf && chmod +x phpbf");

echo shell_exec("./phpbf");
