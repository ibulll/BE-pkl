<?php

// File: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;


class GetEmailController extends Controller
{
    public function getEmails()
    {
        $emails = Email::all(); // Sesuaikan dengan model dan tabel yang Anda gunakan
        return response()->json($emails);
    }
}

