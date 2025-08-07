<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'max_loans_allowed',
            'value' => '3',
            'description' => 'Maximum number of books a user is allowed to borrow',
        ]);
        Setting::create([
            'key' => 'loan_duration_days',
            'value' => '7',
            'description' => 'Duration of book loan in days',
        ]);
        Setting::create([
            'key' => 'late_fee_per_day',
            'value' => '1000',
            'description' => 'Late return fee per day in Indonesian Rupiah (IDR)',
        ]);
    }
}
