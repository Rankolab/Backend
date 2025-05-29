
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RankolabBotController extends Controller
{
    public function index()
    {
        return view('admin.bot.index');
    }

    public function chat(Request $request)
    {
        $query = strtolower($request->input('message'));

        // Basic logic (expand with AI/LLM later)
        if (str_contains($query, 'list users')) {
            $response = \App\Models\User::take(5)->get()->toArray();
        } elseif (str_contains($query, 'run sync')) {
            Artisan::call('rankolab:sync-plugins');
            $response = 'Plugin sync command executed.';
        } elseif (str_contains($query, 'clear cache')) {
            Artisan::call('optimize:clear');
            $response = 'Cache cleared successfully.';
        } else {
            $response = 'Sorry, I did not understand. Please try again or rephrase.';
        }

        return response()->json(['reply' => $response]);
    }
}
