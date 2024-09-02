<?php

namespace App\Repositories;

use App\Models\Appreciation;
use Illuminate\Database\Eloquent\Collection;

class AppreciationRepository
{
    public function all(): Collection
    {
        return Appreciation::orderBy('position')->get();
    }

    /** @param array<string,string> $data */
    public function insert(array $data): Appreciation
    {
        $appreciation = new Appreciation();
        $appreciation->fill($data);
        $appreciation->is_active = array_key_exists('is_active', $data) ? true : false;
        $appreciation->save();

        return $appreciation;
    }

    /** @param array<string,string> $data */
    public function update(Appreciation $appreciation, array $data): Appreciation
    {
        $appreciation->fill($data);
        $appreciation->is_active = array_key_exists('is_active', $data) ? true : false;
        $appreciation->save();

        return $appreciation;
    }

    public function delete(Appreciation $appreciation): void
    {
        $appreciation->delete();
    }
}
