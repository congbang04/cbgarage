<?php

namespace App\Services;

use App\Models\Service;

class ServiceService
{
    public function getAll()
    {
        return Service::orderBy('name')->get();
    }

    public function find($id)
    {
        return Service::findOrFail($id);
    }
}
