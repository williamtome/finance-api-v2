<?php

namespace App\Hateoas;

use App\Models\Revenue;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class RevenueHateoas
{
    use CreatesLinks;

    /**
     * Get the HATEOAS link to view the revenue.
     *
     * @param \App\Models\Revenue $revenue
     *
     * @return null|Link
     */
    public function self(Revenue $revenue): ?Link
    {
        return $this->link('revenue.show', ['revenue' => $revenue]);
    }

    public function update(Revenue $revenue): ?Link
    {
        return $this->link('revenue.update', ['revenue' => $revenue]);
    }

    public function delete(Revenue $revenue): ?Link
    {
        return $this->link('revenue.destroy', ['revenue' => $revenue]);
    }
}
