<?php

namespace AppBundle\Process;

use Symfony\Component\Process\Process;

class Rst2HtmlProcess extends Process
{
    protected $fetchBody = true;

    public function setFetchBody($value)
    {
        $this->fetchBody = (boolean) $value;
    }

    public function convert($input)
    {
        $this->setInput($input);
        $this->run();
        $html = $this->getOutput();

        if ($this->fetchBody) {
            $startpos = strpos($html, '<body>') + 6;
            $endpos   = strpos($html, '</body>');

            return substr($html, $startpos, $endpos - $startpos);
        }

        return $html;
    }
}
