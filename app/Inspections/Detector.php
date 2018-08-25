<?php

namespace App\Inspections;

interface Detector
{
    public function detect(String $body);
}