<?php


namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'attachment' => 'file|mimes:pdf|max:2048', // batasi jenis file dan ukuran
        ]);

        try {
            // Proses attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $attachmentPath = $attachment->store('attachments');
            }

            // Kirim email
            Mail::to($data['to'])->send(new SendEmail($data, $attachmentPath));

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send email', 'error' => $e->getMessage()], 500);
        }
    }
}
