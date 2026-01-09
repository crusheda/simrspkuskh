<?php

namespace App\Modules\AiKlaim\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\AiKlaim\Models\KnowledgeBase;
use App\Modules\AiKlaim\Services\OllamaService;

class AiKlaimController extends Controller
{
    public function index()
    {
        return view('pages.ai-klaim.index');
    }

    public function tanya(Request $request)
    {
        $kb = KnowledgeBase::where('aktif', 1)->pluck('konten')->implode("\n");

        $prompt = "
                    Anda adalah AI khusus klaim RS PKU Muhammadiyah Sukoharjo.

                    Gunakan DATA SOP di bawah ini sebagai acuan utama.
                    Jika jawaban dapat disimpulkan secara logis dari data SOP,
                    jawab dengan bahasa yang jelas dan ringkas.

                    HANYA jika benar-benar tidak ditemukan di data SOP,
                    baru jawab:
                    'Silakan koordinasi dengan Tim Klaim RS.'

                    ====================
                    DATA SOP:
                    $kb
                    ====================

                    PERTANYAAN:
                    {$request->pertanyaan}
                ";

        $jawaban = OllamaService::generate($prompt);

        return response()->json(['jawaban' => $jawaban]);
    }
}

