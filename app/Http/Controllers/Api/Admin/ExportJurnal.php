<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportJurnal extends Controller
{
    public function exportById(Request $request, $id)
    {
        // Query all journal entries with the same user_id
        $jurnals = Jurnal::where('user_id', $id)->get();
    
        if ($jurnals->isEmpty()) {
            return response()->json(['message' => 'Jurnals not found'], 404);
        }
    
        // Prepare the data to be exported
        $data = $jurnals->map(function ($jurnal) {
            // Get the user's name and class for each entry
            $user = User::find($jurnal->user_id);
    
            return [
                'id' => $jurnal->id,
                'status' => $jurnal->status,
                'kegiatan' => $jurnal->kegiatan,
                'waktu_mulai' => $jurnal->waktu_mulai,
                'tanggal_mulai' => $jurnal->tanggal_mulai,
                'waktu_selesai' => $jurnal->waktu_selesai,
                'tanggal_selesai' => $jurnal->tanggal_selesai,
                'name' => $user ? $user->name : '', // Jika user ditemukan, gunakan namanya. Jika tidak, gunakan string kosong
                'kelas' => $user ? $user->kelas : '', // Jika user ditemukan, gunakan kelasnya. Jika tidak, gunakan string kosong
            ];
        });
    
        // Export the data (you can modify the export method based on your needs)
        return $this->exportData($data);
    }
    


    public function exportAll()
    {
        // Get all students (users with role_id 4)
        $students = User::where('role_id', 4)->get();
    
        // Prepare the data to be exported
        $data = [];
    
        foreach ($students as $student) {
            // Query all journal entries for the current student
            $jurnals = Jurnal::where('user_id', $student->id)->get();
    
            // Loop through the journal entries and add them to the export data
            foreach ($jurnals as $jurnal) {
                $data[] = [
                    'id' => $jurnal->id,
                    'status' => $jurnal->status,
                    'kegiatan' => $jurnal->kegiatan,
                    'waktu_mulai' => $jurnal->waktu_mulai,
                    'tanggal_mulai' => $jurnal->tanggal_mulai,
                    'waktu_selesai' => $jurnal->waktu_selesai,
                    'tanggal_selesai' => $jurnal->tanggal_selesai,
                    'name' => $student->name,
                    'kelas' => $student->kelas,
                ];
            }
    
            // If the student has no journal entries, add an empty entry
            if ($jurnals->isEmpty()) {
                $data[] = [
                    'id' => '',
                    'status' => '',
                    'kegiatan' => '',
                    'waktu_mulai' => '',
                    'tanggal_mulai' => '',
                    'waktu_selesai' => '',
                    'tanggal_selesai' => '',
                    'name' => $student->name,
                    'kelas' => $student->kelas,
                ];
            }
        }
    
        // Export the data (you can modify the export method based on your needs)
        return $this->exportData($data);
    }
    
    private function exportData($data)
    {
        // Prepare the CSV file
        $fileName = 'journals.csv';

        $response = new StreamedResponse(function () use ($data) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['Nama', 'Kelas', 'Status', 'Kegiatan', 'Waktu Mulai', 'Tanggal Mulai', 'Waktu Selesai', 'Tanggal Selesai']);

            // Data
            foreach ($data as $row) {
                // Pad the columns to make them aligned
                $nama = str_pad($row['name'], 20); // Menggunakan 'name' sebagai kunci
                $kelas = str_pad($row['kelas'], 10); // Menggunakan 'kelas' sebagai kunci
                $status = str_pad($row['status'], 10);
                $kegiatan = str_pad($row['kegiatan'], 30);
                $waktuMulai = str_pad($row['waktu_mulai'], 60);
                $tanggalMulai = str_pad($row['tanggal_mulai'], 60);
                $waktuSelesai = str_pad($row['waktu_selesai'], 60);
                $tanggalSelesai = str_pad($row['tanggal_selesai'], 60);

                fputcsv($file, [$nama, $kelas, $status, $kegiatan, $waktuMulai, $tanggalMulai, $waktuSelesai, $tanggalSelesai]);
            }

            fclose($file);
        });

        // Set headers for download
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

}
