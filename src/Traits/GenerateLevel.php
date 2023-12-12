<?php

namespace App\Traits;

trait GenerateLevel {
    public function generateLevel(int $expectedSalary): string
    {
        if ($expectedSalary < 5000) {
            return 'Junior';
        }

        if ($expectedSalary < 10000) {
            return 'Regular';
        }

        return 'Senior';
    }
}
