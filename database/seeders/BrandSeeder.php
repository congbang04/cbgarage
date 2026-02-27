<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Toyota', 'country' => 'Japan'],
            ['name' => 'Honda', 'country' => 'Japan'],
            ['name' => 'Hyundai', 'country' => 'South Korea'],
            ['name' => 'Kia', 'country' => 'South Korea'],
            ['name' => 'Mazda', 'country' => 'Japan'],
            ['name' => 'Mitsubishi', 'country' => 'Japan'],
            ['name' => 'Ford', 'country' => 'USA'],
            ['name' => 'Chevrolet', 'country' => 'USA'],
            ['name' => 'Mercedes-Benz', 'country' => 'Germany'],
            ['name' => 'BMW', 'country' => 'Germany'],
            ['name' => 'Audi', 'country' => 'Germany'],
            ['name' => 'Lexus', 'country' => 'Japan'],
            ['name' => 'VinFast', 'country' => 'Vietnam'],
            ['name' => 'Isuzu', 'country' => 'Japan'],
            ['name' => 'Suzuki', 'country' => 'Japan'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'country' => $brand['country']
            ]);
        }
    }
}
