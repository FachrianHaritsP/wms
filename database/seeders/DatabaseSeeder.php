<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Rack;
use App\Models\RackSlot;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        User::create([
            'id' => 1,
            'name' => 'Owner Demo',
            'email' => 'owner@demo.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $leader = User::create([
            'id' => 2,
            'name' => 'Leader Demo',
            'email' => 'leader@demo.com',
            'password' => Hash::make('password'),
            'role' => 'leader',
        ]);

        User::create([
            'id' => 3,
            'name' => 'Staff Demo',
            'email' => 'staff@demo.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);


        /*
        |--------------------------------------------------------------------------
        | RACK
        |--------------------------------------------------------------------------
        */

        $rack = Rack::create([
            'rack_code' => 'RACK-A',
            'description' => 'Rak utama demo',
        ]);


        /*
        |--------------------------------------------------------------------------
        | RACK SLOTS
        |--------------------------------------------------------------------------
        */

        $slotA01 = RackSlot::create([
            'rack_id' => $rack->id,
            'slot_code' => 'A-01',
        ]);

        $slotA02 = RackSlot::create([
            'rack_id' => $rack->id,
            'slot_code' => 'A-02',
        ]);

        $slotA03 = RackSlot::create([
            'rack_id' => $rack->id,
            'slot_code' => 'A-03',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        $products = [
            // CUPRO MAZA - CHOCOLATE
            [
                'sku' => 'T006HOUAB260001',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'CHOCOLATE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260002',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'CHOCOLATE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260003',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'CHOCOLATE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],

            // CUPRO MAZA - PLUM NOIR
            [
                'sku' => 'T006HOUAB260004',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'PLUM NOIR',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260005',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'PLUM NOIR',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260006',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'PLUM NOIR',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],

            // CUPRO MAZA - FABULOUSE
            [
                'sku' => 'T006HOUAB260007',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'FABULOUSE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260008',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'FABULOUSE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260009',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'FABULOUSE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],

            // CUPRO MAZA - FADEO DENIM
            [
                'sku' => 'T006HOUAB260010',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'FADEO DENIM',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260011',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'FADEO DENIM',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260012',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'FADEO DENIM',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],

            // CUPRO MAZA - GREY WHALE
            [
                'sku' => 'T006HOUAB260013',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'GREY WHALE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260014',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'GREY WHALE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],
            [
                'sku' => 'T006HOUAB260015',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'GREY WHALE',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA01->id,
            ],

            // CUPRO MAZA - MILKY SAND
            [
                'sku' => 'T006HOUAB260016',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'MILKY SAND',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260017',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'MILKY SAND',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260018',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'MILKY SAND',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA02->id,
            ],

            // CUPRO MAZA - FROSTED MINT
            [
                'sku' => 'T006HOUAB260019',
                'name' => 'CUPRO MAZA',
                'size' => 'M',
                'color' => 'FROSTED MINT',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260020',
                'name' => 'CUPRO MAZA',
                'size' => 'L',
                'color' => 'FROSTED MINT',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260021',
                'name' => 'CUPRO MAZA',
                'size' => 'XL',
                'color' => 'FROSTED MINT',
                'stock' => 5,
                'price' => 438000,
                'rack_slot_id' => $slotA02->id,
            ],

            // ALICE - STEEL BLUE
            [
                'sku' => 'T006HOUAB260022',
                'name' => 'ALICE',
                'size' => 'M',
                'color' => 'STEEL BLUE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260023',
                'name' => 'ALICE',
                'size' => 'L',
                'color' => 'STEEL BLUE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260024',
                'name' => 'ALICE',
                'size' => 'XL',
                'color' => 'STEEL BLUE',
                'stock' => 10,
                'price' => 345000,
                'rack_slot_id' => $slotA02->id,
            ],

            // ALICE - BROWN
            [
                'sku' => 'T006HOUAB260025',
                'name' => 'ALICE',
                'size' => 'M',
                'color' => 'BROWN',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260026',
                'name' => 'ALICE',
                'size' => 'L',
                'color' => 'BROWN',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA02->id,
            ],
            [
                'sku' => 'T006HOUAB260027',
                'name' => 'ALICE',
                'size' => 'XL',
                'color' => 'BROWN',
                'stock' => 10,
                'price' => 345000,
                'rack_slot_id' => $slotA02->id,
            ],

            // ALICE - LIGHT BEIGE
            [
                'sku' => 'T006HOUAB260028',
                'name' => 'ALICE',
                'size' => 'M',
                'color' => 'LIGHT BEIGE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260029',
                'name' => 'ALICE',
                'size' => 'L',
                'color' => 'LIGHT BEIGE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260030',
                'name' => 'ALICE',
                'size' => 'XL',
                'color' => 'LIGHT BEIGE',
                'stock' => 10,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],

            // ALICE - DEEP MAHOGANY
            [
                'sku' => 'T006HOUAB260031',
                'name' => 'ALICE',
                'size' => 'M',
                'color' => 'DEEP MAHOGANY',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260032',
                'name' => 'ALICE',
                'size' => 'L',
                'color' => 'DEEP MAHOGANY',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260033',
                'name' => 'ALICE',
                'size' => 'XL',
                'color' => 'DEEP MAHOGANY',
                'stock' => 10,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],

            // ALICE - MISTY SAGE
            [
                'sku' => 'T006HOUAB260034',
                'name' => 'ALICE',
                'size' => 'M',
                'color' => 'MISTY SAGE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260035',
                'name' => 'ALICE',
                'size' => 'L',
                'color' => 'MISTY SAGE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260036',
                'name' => 'ALICE',
                'size' => 'XL',
                'color' => 'MISTY SAGE',
                'stock' => 10,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],

            // ALICE - DUSTY ROSE
            [
                'sku' => 'T006HOUAB260037',
                'name' => 'ALICE',
                'size' => 'M',
                'color' => 'DUSTY ROSE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260038',
                'name' => 'ALICE',
                'size' => 'L',
                'color' => 'DUSTY ROSE',
                'stock' => 15,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
            [
                'sku' => 'T006HOUAB260039',
                'name' => 'ALICE',
                'size' => 'XL',
                'color' => 'DUSTY ROSE',
                'stock' => 10,
                'price' => 345000,
                'rack_slot_id' => $slotA03->id,
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }


        /*
        |--------------------------------------------------------------------------
        | STOCK TRANSACTIONS
        |--------------------------------------------------------------------------
        |
        | Database menggunakan UTC.
        | Contoh:
        | 09:00 WIB = 02:00 UTC
        |
        | Semua transaksi dilakukan oleh Leader (ID 2).
        |
        */

        $products = Product::all();

        $transactionData = [
            // 20 Agustus
            ['20 02:00', 'in', 20],
            ['20 04:00', 'out', 3],
            ['20 07:00', 'in', 15],
            ['20 09:00', 'out', 5],

            // 21 Agustus
            ['21 01:00', 'in', 15],
            ['21 03:00', 'out', 4],
            ['21 06:00', 'in', 20],
            ['21 08:00', 'out', 6],

            // 22 Agustus
            ['22 02:00', 'in', 25],
            ['22 05:00', 'out', 5],
            ['22 08:00', 'in', 10],
            ['22 10:00', 'out', 3],

            // 23 Agustus
            ['23 01:00', 'in', 18],
            ['23 04:00', 'out', 4],
            ['23 07:00', 'in', 22],
            ['23 09:00', 'out', 7],

            // 24 Agustus
            ['24 02:00', 'in', 30],
            ['24 05:00', 'out', 8],
            ['24 08:00', 'in', 15],
            ['24 10:00', 'out', 5],

            // 25 Agustus
            ['25 01:00', 'in', 20],
            ['25 03:00', 'out', 4],
            ['25 07:00', 'in', 25],
            ['25 09:00', 'out', 6],

            // 26 Agustus
            ['26 02:00', 'in', 35],
            ['26 04:00', 'out', 7],
            ['26 07:00', 'in', 20],
            ['26 09:00', 'out', 5],
        ];

        foreach ($transactionData as $index => [$dateTime, $type, $qty]) {

            $product = $products[$index % $products->count()];

            StockTransaction::create([
                'product_id' => $product->id,
                'type' => $type,
                'qty' => $qty,
                'created_by' => $leader->id,
                'created_at' => '2026-08-' . $dateTime . ':00',
                'updated_at' => '2026-08-' . $dateTime . ':00',
            ]);
        }
    }
}