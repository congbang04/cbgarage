<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $customers = Customer::all();
        $brands = Brand::with('carModels')->get();

        if ($customers->isEmpty() || $brands->isEmpty()) {
            return;
        }

        foreach ($customers as $customer) {

            // mỗi khách có 1–3 xe
            $carCount = rand(1, 3);

            for ($i = 0; $i < $carCount; $i++) {

                $brand = $brands->random();
                $model = $brand->carModels->random();

                $year = rand(2015, now()->year);

                Car::create([
                    'customer_id'   => $customer->id,
                    'brand_id'      => $brand->id,
                    'car_model_id'  => $model->id,

                    // Biển số VN: 51A-123.45
                    'license_plate' => $this->generateVietnamPlate(),

                    'manufacture_year' => $year,

                    'color' => $faker->randomElement([
                        'Trắng',
                        'Đen',
                        'Bạc',
                        'Xám',
                        'Đỏ',
                        'Xanh',
                        'Nâu',
                        'Vàng'
                    ]),

                    // ODO hợp lý: 10k - 25k / năm
                    'current_odo' => rand(
                        ($year - 2015) * 10000,
                        ($year - 2015) * 25000 + 20000
                    ),

                    // VIN giả lập (17 ký tự)
                    'vin_number' => strtoupper(Str::random(17)),

                    'created_at' => $faker->dateTimeBetween('-2 years', '-1 month'),
                    'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        }
    }

    /**
     * Generate biển số xe Việt Nam
     */
    private function generateVietnamPlate(): string
    {
        $provinceCodes = [
            '11',
            '12',
            '14',
            '15',
            '16',
            '17',
            '18',
            '19',
            '20',
            '21',
            '22',
            '23',
            '24',
            '25',
            '26',
            '27',
            '28',
            '29',
            '30',
            '31',
            '32',
            '33',
            '34',
            '35',
            '36',
            '37',
            '38',
            '39',
            '40',
            '41',
            '43',
            '47',
            '48',
            '49',
            '50',
            '51',
            '52',
            '53',
            '54',
            '55',
            '56',
            '57',
            '58',
            '59',
            '60',
            '61',
            '62',
            '63',
            '64',
            '65',
            '66',
            '67',
            '68',
            '69',
            '70',
            '71',
            '72',
            '73',
            '74',
            '75',
            '76',
            '77',
            '78',
            '79',
            '80',
            '81',
            '82',
            '83',
            '84',
            '85',
            '86',
            '88',
            '89',
            '90',
            '92',
            '93',
            '94',
            '95',
            '97',
            '98',
            '99'
        ];

        $code = $provinceCodes[array_rand($provinceCodes)];
        $letter = chr(rand(65, 90)); // A-Z

        return $code . $letter . '-' . rand(100, 999) . '.' . rand(10, 99);
    }
}
