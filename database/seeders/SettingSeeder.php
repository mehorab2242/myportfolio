<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed default admin brand colours.
     */
    public function run(): void
    {
        Setting::current();
    }
}
