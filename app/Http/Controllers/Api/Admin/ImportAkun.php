<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class ImportAkun extends Controller
{

    public function importStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }
    
        try {
            $filePath = $request->file('file')->getPathname();
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
    
            $columns = range('A', $worksheet->getHighestDataColumn());
            $headerRow = 1;
    
            $columnMapping = [
                'name' => null,
                'email' => null,
                'nisn' => null,
                'kelas' => null,
                'password' => null,
            ];
    
            // Pemetaan kolom berdasarkan header
            foreach ($columns as $column) {
                $cellValue = $worksheet->getCell($column . $headerRow)->getValue();
                if (in_array(strtolower($cellValue), array_keys($columnMapping))) {
                    $columnMapping[strtolower($cellValue)] = $column;
                }
            }
    
            // Cek apakah semua kolom yang diperlukan ditemukan
            if (in_array(null, $columnMapping)) {
                return response()->json(['error' => 'Salah satu atau lebih kolom yang diperlukan tidak ditemukan dalam file.']);
            }
    
            $dataStartRow = 2;
            $dataEndRow = $worksheet->getHighestDataRow();
    
            $studentsData = [];
    
            for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                $student = [];
                $isRowEmpty = true;
                foreach ($columnMapping as $key => $column) {
                    $value = $worksheet->getCell($column . $row)->getValue();
                    $student[$key] = $value;
                    if (!empty($value)) {
                        $isRowEmpty = false;
                    }
                }
                if ($isRowEmpty) {
                    // Jika baris kosong, lewati baris ini
                    continue;
                }
                // Cek apakah semua data yang diperlukan ada
                if (array_search(null, $student, true) !== false) {
                    return response()->json(['error' => "Data tidak lengkap pada baris $row. Semua kolom harus diisi."]);
                }
                $studentsData[] = $student;
            }
    
            foreach ($studentsData as $data) {
                $user = User::create($data);

                $user->assignRole('siswa');
            }
    
            return response()->json(['success' => 'Data siswa berhasil di-import!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan dalam mengimpor data siswa: ' . $e->getMessage()]);
        }
    }

}
