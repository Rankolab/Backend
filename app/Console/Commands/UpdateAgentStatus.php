<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agent;
use Carbon\Carbon;

class UpdateAgentStatus extends Command
{
    protected $signature = 'agent:update-status';
    protected $description = 'Update agent status based on last heartbeat';

    public function handle()
    {
        $timeoutMinutes = 10;
        $now = Carbon::now();

        $updated = Agent::query()
            ->where('last_heartbeat', '<', $now->subMinutes($timeoutMinutes))
            ->update(['status' => 'offline']);

        $this->info("Updated $updated agents to offline.");
    }
}
