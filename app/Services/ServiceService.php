<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ServiceService
{
    public function getAll()
    {
        return Service::orderByDesc('created_at')->get();
    }

    public function find($id)
    {
        return Service::findOrFail($id);
    }
    public function create(array $data): Service
    {
        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            $data['icon'] = $data['icon']->store('services', 'public');
        }

        return Service::create($data);
    }
    public function destroy(int $id): bool
    {
        $service = $this->find($id);

        if ($service->icon) {
            Storage::disk()->delete($service->icon);
        }

        return $service->delete();
    }
    public function update(Service $service, array $data)
    {
        $service->update($data);
        return $service;
    }
}
