<?php 

namespace App\Inspections;

use Exception;

class KeyHeldDown implements Detector
{
    public function detect(String $body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new Exception('Your reply contians spam!');
        }
    }
}