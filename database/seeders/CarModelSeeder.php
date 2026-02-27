<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            'Toyota' => [
                'Vios',
                'Corolla Altis',
                'Corolla Cross',
                'Camry',
                'Fortuner',
                'Innova',
                'Innova Cross',
                'Hilux',
                'Yaris',
                'Raize',
                'Avanza',
                'Veloz Cross',
                'Land Cruiser',
                'Land Cruiser Prado',
                'Wigo',
                'Rush'
            ],

            'Honda' => [
                'City',
                'Civic',
                'CR-V',
                'HR-V',
                'BR-V',
                'Accord',
                'Jazz',
                'Pilot'
            ],

            'Hyundai' => [
                'Accent',
                'Elantra',
                'Tucson',
                'Santa Fe',
                'Creta',
                'Kona',
                'Grand i10',
                'Stargazer',
                'Palisade'
            ],

            'Kia' => [
                'Morning',
                'Soluto',
                'K3',
                'K5',
                'Seltos',
                'Sonet',
                'Sorento',
                'Carnival'
            ],

            'Mazda' => [
                'Mazda2',
                'Mazda3',
                'Mazda6',
                'CX-3',
                'CX-5',
                'CX-8',
                'BT-50'
            ],

            'Mitsubishi' => [
                'Xpander',
                'Xpander Cross',
                'Attrage',
                'Outlander',
                'Pajero Sport',
                'Triton'
            ],

            'Ford' => [
                'Ranger',
                'Everest',
                'EcoSport',
                'Territory',
                'Explorer',
                'Transit'
            ],

            'Chevrolet' => [
                'Spark',
                'Cruze',
                'Colorado',
                'Trailblazer',
                'Captiva'
            ],

            'Mercedes-Benz' => [
                'C200',
                'C300',
                'E200',
                'E300',
                'S450',
                'GLA',
                'GLC',
                'GLE',
                'GLS'
            ],

            'BMW' => [
                '320i',
                '330i',
                '520i',
                '530i',
                'X1',
                'X3',
                'X5',
                'X7'
            ],

            'Audi' => [
                'A4',
                'A6',
                'Q2',
                'Q3',
                'Q5',
                'Q7'
            ],

            'Lexus' => [
                'IS300',
                'ES250',
                'RX350',
                'NX350',
                'LX600'
            ],

            'VinFast' => [
                'Fadil',
                'Lux A2.0',
                'Lux SA2.0',
                'VF e34',
                'VF 5',
                'VF 6',
                'VF 7',
                'VF 8',
                'VF 9'
            ],

            'Isuzu' => [
                'D-Max',
                'mu-X'
            ],

            'Suzuki' => [
                'Swift',
                'Ciaz',
                'Ertiga',
                'XL7',
                'Carry'
            ],

        ];

        foreach ($data as $brandName => $models) {

            $brand = Brand::where('name', $brandName)->first();

            if ($brand) {
                foreach ($models as $modelName) {
                    CarModel::create([
                        'brand_id' => $brand->id,
                        'name' => $modelName
                    ]);
                }
            }
        }
    }
}
