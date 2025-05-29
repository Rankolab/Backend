<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiTool;

class AiToolSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/ai_tools_seed.json'));
        $tools = json_decode($json, true);

        foreach ($tools as $tool) {
            AiTool::create($tool);
        }
    }
}
