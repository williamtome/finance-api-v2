<?php

namespace App\Http\Traits;

trait FormatterDateTrait
{
    /**
     * @throws \Exception
     */
    public function formatDate(string $year, string $month): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
