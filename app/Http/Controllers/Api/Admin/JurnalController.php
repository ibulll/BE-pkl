<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Jurnal;
use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JurnalController extends Controller
{
    public function index()
    {
        try {
            // Fetch a specific user and their journal (replace 1 with the user's ID)
            $user = User::find(1);
            $jurnalForUser = $user ? $user->jurnal : null;

            // Fetch all journals with user information
            $jurnals = Jurnal::with('user')->get();

            // Transform data before sending it as a response
            $formattedJurnals = $jurnals->map(function ($jurnal) {
                return [
                    'id' => $jurnal->id,
                    'user_name' => $jurnal->user ? $jurnal->user->name : 'Nama Tidak Tersedia',
                    'status' => $jurnal->status,
                    'kegiatan' => $jurnal->kegiatan,
                    'tanggal' => $jurnal->tanggal,
                    // Add other properties as needed
                ];
            });

            return response()->json([
                'user_jurnal' => [
                    'user_name' => $user ? $user->name : 'Nama Tidak Tersedia',
                    'jurnal' => $jurnalForUser,
                ],
                'all_jurnals' => $formattedJurnals,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data' . $e->getMessage()], 500);
        }
    }

    // public function updateStatus(Request $request, $jurnalId)
    // {
    //     try {
    //         // Validate the request data
    //         $request->validate([
    //             'status' => 'required|in:proses,selesai', // Assuming status can only be 'proses' or 'selesai'
    //         ]);

    //         // Update the status in the database
    //         Jurnal::where('id', $jurnalId)->update([
    //             'status' => $request->input('status'),
    //         ]);

    //         return response()->json(['message' => 'Status updated successfully']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Error updating jurnal status: ' . $e->getMessage()], 500);
    //     }
    // }
}
