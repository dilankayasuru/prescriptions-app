<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol',
                'dosage' => '500mg',
                'unit_price' => 2.50,
                'stock' => 1000,
            ],
            [
                'name' => 'Ibuprofen',
                'dosage' => '400mg',
                'unit_price' => 3.75,
                'stock' => 800,
            ],
            [
                'name' => 'Aspirin',
                'dosage' => '75mg',
                'unit_price' => 1.25,
                'stock' => 500,
            ],
            [
                'name' => 'Diclofenac',
                'dosage' => '50mg',
                'unit_price' => 4.50,
                'stock' => 300,
            ],

            // Antibiotics
            [
                'name' => 'Amoxicillin',
                'dosage' => '500mg',
                'unit_price' => 8.75,
                'stock' => 400,
            ],
            [
                'name' => 'Azithromycin',
                'dosage' => '250mg',
                'unit_price' => 12.50,
                'stock' => 200,
            ],
            [
                'name' => 'Ciprofloxacin',
                'dosage' => '500mg',
                'unit_price' => 15.25,
                'stock' => 150,
            ],
            [
                'name' => 'Cephalexin',
                'dosage' => '500mg',
                'unit_price' => 9.80,
                'stock' => 250,
            ],

            // Cardiovascular
            [
                'name' => 'Amlodipine',
                'dosage' => '5mg',
                'unit_price' => 6.25,
                'stock' => 600,
            ],
            [
                'name' => 'Atenolol',
                'dosage' => '50mg',
                'unit_price' => 5.50,
                'stock' => 400,
            ],
            [
                'name' => 'Lisinopril',
                'dosage' => '10mg',
                'unit_price' => 7.75,
                'stock' => 350,
            ],
            [
                'name' => 'Metoprolol',
                'dosage' => '25mg',
                'unit_price' => 8.25,
                'stock' => 300,
            ],

            // Diabetes
            [
                'name' => 'Metformin',
                'dosage' => '500mg',
                'unit_price' => 3.25,
                'stock' => 800,
            ],
            [
                'name' => 'Glibenclamide',
                'dosage' => '5mg',
                'unit_price' => 4.75,
                'stock' => 400,
            ],

            // Respiratory
            [
                'name' => 'Salbutamol',
                'dosage' => '100mcg',
                'unit_price' => 12.50,
                'stock' => 200,
            ],
            [
                'name' => 'Prednisolone',
                'dosage' => '5mg',
                'unit_price' => 6.75,
                'stock' => 300,
            ],
            [
                'name' => 'Cetirizine',
                'dosage' => '10mg',
                'unit_price' => 2.25,
                'stock' => 600,
            ],

            // Gastrointestinal
            [
                'name' => 'Omeprazole',
                'dosage' => '20mg',
                'unit_price' => 5.25,
                'stock' => 500,
            ],
            [
                'name' => 'Ranitidine',
                'dosage' => '150mg',
                'unit_price' => 3.50,
                'stock' => 400,
            ],
            [
                'name' => 'Domperidone',
                'dosage' => '10mg',
                'unit_price' => 4.25,
                'stock' => 350,
            ],

            // Vitamins & Supplements
            [
                'name' => 'Vitamin D3',
                'dosage' => '1000IU',
                'unit_price' => 8.50,
                'stock' => 400,
            ],
            [
                'name' => 'Folic Acid',
                'dosage' => '5mg',
                'unit_price' => 2.75,
                'stock' => 500,
            ],
            [
                'name' => 'Iron Sulfate',
                'dosage' => '200mg',
                'unit_price' => 3.25,
                'stock' => 300,
            ],
            [
                'name' => 'Calcium Carbonate',
                'dosage' => '500mg',
                'unit_price' => 4.50,
                'stock' => 450,
            ],

            // Topical & External
            [
                'name' => 'Hydrocortisone Cream',
                'dosage' => '1%',
                'unit_price' => 6.75,
                'stock' => 200,
            ],
            [
                'name' => 'Clotrimazole Cream',
                'dosage' => '1%',
                'unit_price' => 8.25,
                'stock' => 150,
            ],

            // Mental Health
            [
                'name' => 'Sertraline',
                'dosage' => '50mg',
                'unit_price' => 12.75,
                'stock' => 200,
            ],
            [
                'name' => 'Lorazepam',
                'dosage' => '1mg',
                'unit_price' => 15.50,
                'stock' => 100,
            ],

            // Others
            [
                'name' => 'Levothyroxine',
                'dosage' => '50mcg',
                'unit_price' => 7.25,
                'stock' => 300,
            ],
            [
                'name' => 'Warfarin',
                'dosage' => '5mg',
                'unit_price' => 9.50,
                'stock' => 150,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
