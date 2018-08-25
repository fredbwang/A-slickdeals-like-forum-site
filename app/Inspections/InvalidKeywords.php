<?php 

namespace App\Inspections;

use Exception;

class InvalidKeywords implements Detector
{
    protected $invalidwords = [
        'spam'
    ];

    public function detect(String $body)
    {
        foreach ($this->invalidwords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam!');
            }
        }
    }
}