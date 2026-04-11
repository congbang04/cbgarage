<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    private array $carsData = [

        // Toyota
        ['brand' => 'Toyota', 'model' => 'Camry'],
        ['brand' => 'Toyota', 'model' => 'Vios'],
        ['brand' => 'Toyota', 'model' => 'Corolla Cross'],
        ['brand' => 'Toyota', 'model' => 'Fortuner'],
        ['brand' => 'Toyota', 'model' => 'Hilux'],
        ['brand' => 'Toyota', 'model' => 'Raize'],

        // Honda
        ['brand' => 'Honda', 'model' => 'Civic'],
        ['brand' => 'Honda', 'model' => 'City'],
        ['brand' => 'Honda', 'model' => 'CR-V'],
        ['brand' => 'Honda', 'model' => 'HR-V'],

        // Hyundai
        ['brand' => 'Hyundai', 'model' => 'Accent'],
        ['brand' => 'Hyundai', 'model' => 'Elantra'],
        ['brand' => 'Hyundai', 'model' => 'Tucson'],
        ['brand' => 'Hyundai', 'model' => 'Santa Fe'],
        ['brand' => 'Hyundai', 'model' => 'Creta'],

        // Kia
        ['brand' => 'Kia', 'model' => 'K3'],
        ['brand' => 'Kia', 'model' => 'K5'],
        ['brand' => 'Kia', 'model' => 'Seltos'],
        ['brand' => 'Kia', 'model' => 'Sonet'],
        ['brand' => 'Kia', 'model' => 'Carnival'],

        // Mazda
        ['brand' => 'Mazda', 'model' => 'Mazda3'],
        ['brand' => 'Mazda', 'model' => 'Mazda6'],
        ['brand' => 'Mazda', 'model' => 'CX-3'],
        ['brand' => 'Mazda', 'model' => 'CX-5'],
        ['brand' => 'Mazda', 'model' => 'CX-8'],

        // Mitsubishi
        ['brand' => 'Mitsubishi', 'model' => 'Xpander'],
        ['brand' => 'Mitsubishi', 'model' => 'Xpander Cross'],
        ['brand' => 'Mitsubishi', 'model' => 'Attrage'],
        ['brand' => 'Mitsubishi', 'model' => 'Triton'],

        // Ford
        ['brand' => 'Ford', 'model' => 'Ranger'],
        ['brand' => 'Ford', 'model' => 'Everest'],
        ['brand' => 'Ford', 'model' => 'Territory'],

        // Chevrolet
        ['brand' => 'Chevrolet', 'model' => 'Spark'],
        ['brand' => 'Chevrolet', 'model' => 'Cruze'],
        ['brand' => 'Chevrolet', 'model' => 'Colorado'],

        // Mercedes
        ['brand' => 'Mercedes-Benz', 'model' => 'C200'],
        ['brand' => 'Mercedes-Benz', 'model' => 'C300'],
        ['brand' => 'Mercedes-Benz', 'model' => 'E300'],
        ['brand' => 'Mercedes-Benz', 'model' => 'GLC'],

        // BMW
        ['brand' => 'BMW', 'model' => '320i'],
        ['brand' => 'BMW', 'model' => '520i'],
        ['brand' => 'BMW', 'model' => 'X3'],
        ['brand' => 'BMW', 'model' => 'X5'],

        // Audi
        ['brand' => 'Audi', 'model' => 'A4'],
        ['brand' => 'Audi', 'model' => 'A6'],
        ['brand' => 'Audi', 'model' => 'Q5'],

        // Lexus
        ['brand' => 'Lexus', 'model' => 'ES250'],
        ['brand' => 'Lexus', 'model' => 'RX350'],
        ['brand' => 'Lexus', 'model' => 'NX350'],

        // VinFast
        ['brand' => 'VinFast', 'model' => 'Fadil'],
        ['brand' => 'VinFast', 'model' => 'VF e34'],
        ['brand' => 'VinFast', 'model' => 'VF 5'],
        ['brand' => 'VinFast', 'model' => 'VF 8'],

        // Isuzu
        ['brand' => 'Isuzu', 'model' => 'D-Max'],
        ['brand' => 'Isuzu', 'model' => 'mu-X'],

        // Suzuki
        ['brand' => 'Suzuki', 'model' => 'Swift'],
        ['brand' => 'Suzuki', 'model' => 'XL7'],
        ['brand' => 'Suzuki', 'model' => 'Ertiga'],
    ];

    private function downloadCarImage($brand, $model)
    {
        $fileName = 'cars/' . Str::slug($brand . '-' . $model) . '.jpg';

        // ✅ Nếu đã có rồi thì không tải lại
        if (Storage::disk('public')->exists($fileName)) {
            return $fileName;
        }

        $query = $brand . ' ' . $model . ' car';

        $response = Http::get('https://api.unsplash.com/search/photos', [
            'query' => $query,
            'per_page' => 1,
            'client_id' => env('UNSPLASH_ACCESS_KEY'),
        ]);

        if ($response->successful() && !empty($response['results'])) {
            $imageUrl = $response['results'][0]['urls']['regular'];

            try {
                $image = file_get_contents($imageUrl);
                Storage::disk('public')->put($fileName, $image);

                sleep(1); // tránh rate limit

                return $fileName;
            } catch (\Exception $e) {
                return 'cars/default.jpg';
            }
        }

        return 'cars/default.jpg';
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $customers = Customer::all();
        $brands = Brand::with('carModels')->get();

        foreach ($customers as $customer) {

            $cars = collect($this->carsData)
                ->unique(fn($c) => $c['brand'] . '-' . $c['model'])
                ->random(rand(2, 4));

            foreach ($cars as $carData) {

                $brand = $brands->where('name', $carData['brand'])->first();

                if (!$brand) continue;

                $model = $brand->carModels
                    ->where('name', $carData['model'])
                    ->first();

                if (!$model) continue;

                Car::create([
                    'customer_id'   => $customer->id,
                    'brand_id'      => $brand->id,
                    'car_model_id'  => $model->id,

                    'image' => $this->downloadCarImage(
                        $carData['brand'],
                        $carData['model']
                    ),

                    'license_plate' => $this->generateVietnamPlate(),
                    'manufacture_year' => rand(2018, 2023),

                    'color' => $faker->randomElement([
                        'Trắng',
                        'Đen',
                        'Đỏ',
                        'Xám',
                        'Bạc',
                        'Nâu',
                        'Xanh đậm',
                        'Xanh lam',
                        'Vàng',
                        'Cam'
                    ]),

                    'current_odo' => rand(10000, 80000),

                    'vin_number' => strtoupper(Str::random(17)),
                ]);
            }
        }
    }
    /**
     * Generate biển số xe Việt Nam - Format: [Tỉnh][Letter]-[Số]
     * VD: 30A-12345 (TP.HCM), 29B-45678 (Bà Rịa - Vũng Tàu)
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
            '28', // Miền Bắc & Trung
            '29', // Bà Rịa - Vũng Tàu
            '30',
            '31',
            '32',
            '33',
            '34',
            '35',
            '36',
            '37',
            '38',
            '39', // TP.HCM & xung quanh
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
            '59', // Miền Nam
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
        $number = str_pad(rand(1000, 99999), 5, '0', STR_PAD_LEFT);

        return $code . $letter . '-' . $number;
    }
}
