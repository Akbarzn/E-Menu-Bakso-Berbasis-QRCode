<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    //
    public function show(){
        $link = url('/menu');
        $qrCode = QrCode::size(300)->generate($link);
        return view('public.dashboard', compact('qrCode','link'));
    }
}
