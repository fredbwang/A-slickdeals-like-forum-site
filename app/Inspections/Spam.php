<?php

namespace App\Inspections;

use PHPUnit\Runner\Exception;

class Spam
{
    protected $detectors = [
        InvalidKeywords::class,
        KeyHeldDown::class,
    ];

    public function detect($body)
    {
        
        foreach ($this->detectors as $detector) {
            app($detector)->detect($body);
        }

        return false;
    }
}

