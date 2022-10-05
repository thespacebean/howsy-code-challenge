<?php

namespace App\Models;

use Carbon\Carbon;

class User
{
    public string $id;
    public string $email;
    public string $name;
    public ?string $contract_start;
    public ?string $contract_end;

    public function __construct($id, $email, $name, $contract_start = null, $contract_end = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->contract_start = $contract_start;
        $this->contract_end = $contract_end;
    }

    public function hasTwelveMonthContract(): bool
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->contract_start);
        $end = Carbon::parse($this->contract_end);
        $diff = $start->diffInYears($end);

        if(($end > $now) && $diff >= 1)
        {
            return true;
        }

        return false;
    }
}