<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toyota = Brand::where('name', 'Toyota')->first();
        $honda  = Brand::where('name', 'Honda')->first();
        $ford   = Brand::where('name', 'Ford')->first();

        $vios   = CarModel::where('name', 'Vios')->where('brand_id', $toyota->id)->first();
        $civic  = CarModel::where('name', 'Civic')->where('brand_id', $honda->id)->first();
        $ranger = CarModel::where('name', 'Ranger')->where('brand_id', $ford->id)->first();

        Car::create([
            'customer_id'   => 1,
            'brand_id'      => $toyota->id,
            'car_model_id'  => $vios->id,
            'license_plate' => '51A-12345',
            'manufacture_year' => 2020,
            'color'         => 'Trắng',
            'current_odo'   => 45000,
            'vin_number'    => 'VINTOYOTA123456789'
        ]);

        Car::create([
            'customer_id'   => 1,
            'brand_id'      => $honda->id,
            'car_model_id'  => $civic->id,
            'license_plate' => '60B-67890',
            'manufacture_year' => 2021,
            'color'         => 'Đen',
            'current_odo'   => 32000,
            'vin_number'    => 'VINHONDA987654321'
        ]);

        Car::create([
            'customer_id'   => 1,
            'brand_id'      => $ford->id,
            'car_model_id'  => $ranger->id,
            'license_plate' => '59C-24680',
            'manufacture_year' => 2019,
            'color'         => 'Xám',
            'current_odo'   => 78000,
            'vin_number'    => 'VINFORD112233445'
        ]);
    }
}
