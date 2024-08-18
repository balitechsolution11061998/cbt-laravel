<?php

namespace App\Http\Controllers;

use App\Models\HasilUjian;
use App\Models\PaketSoal;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianHistory;
use App\Models\UjianHistoryDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UjianController extends Controller
{

    public function index(Request $request)
    {
        return view('ujians.index');
    }

    public function start($ujian_id, $nis, $paketSoal_id)
    {
        try {
            // Retrieve the Ujian, Siswa, and PaketSoal models
            $ujian = Ujian::where('id',$ujian_id)->first();
            $siswa = Siswa::where('id', $nis)->firstOrFail();

            $paketSoal = PaketSoal::findOrFail($paketSoal_id);
            // Create an entry in ujian_histories
            $ujianHistory = UjianHistory::create([
                'ujian_id' => $ujian->id,
                'siswa_id' => $siswa->id,
                'paket_soal_id' => $paketSoal->id,
                'jumlah_benar' => 0,
                'jumlah_salah' => 0,
                'total_nilai' => 0.00,
            ]);

            // Iterate over questions in PaketSoal and create ujian_history_details
            // foreach ($paketSoal->soal as $soal) {
            //     UjianHistoryDetail::create([
            //         'ujian_history_id' => $ujianHistory->id,
            //         'soal_id' => $soal->id,
            //         'jawaban_siswa' => null, // Initialize with null or default value
            //         'jawaban_benar' => $soal->jawaban_benar, // Assuming 'jawaban_benar' is a field in the 'soal' table
            //     ]);
            // }


            // Redirect or return a response as needed
            return redirect()->route('ujian.show', ['ujian' => $ujian->id]);
        } catch (Exception $e) {
            dd($e->getMessage());
            // Log the exception

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while starting the ujian. Please try again.');
        }
    }

    public function data()
    {
        $ujians = Ujian::with('paketSoal', 'mataPelajaran', 'kelas')->get();
        return DataTables::of($ujians)
            ->addIndexColumn() // This will add DT_RowIndex
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-primary btn-sm editUjian" data-id="' . $row->id . '">
                                <i class="fas fa-edit"></i> Edit
                            </button>';
                $deleteBtn = '<button class="btn btn-danger btn-sm deleteUjian" data-id="' . $row->id . '">
                                <i class="fas fa-trash"></i> Delete
                            </button>';
                return $editBtn . ' ' . $deleteBtn;
            })

            ->make(true);
    }

    public function edit($id)
{
    $ujian = Ujian::with('paketSoal', 'mataPelajaran', 'kelas')->findOrFail($id);
    return response()->json($ujian);
}




// public function store(Request $request)
// {

//     // Validate the request data
//     $validatedData = $request->validate([
//         'nama' => 'required|max:255',
//         'paket_soal_id' => 'required|exists:paket_soal,id',
//         'kelas_id' => 'required',
//         'waktu_mulai' => 'required|date',
//         'durasi' => 'required|integer',
//         'poin_benar' => 'required|integer',
//         'poin_salah' => 'required|integer',
//         'poin_tidak_jawab' => 'required|integer',
//         'keterangan' => 'nullable|string',
//         'tampilkan_nilai' => 'nullable|boolean',
//         'tampilkan_hasil' => 'nullable|boolean',
//         'gunakan_token' => 'nullable|boolean',
//         'mata_pelajaran_id' => 'required',
//     ]);

//     // Check if the combination of paket_soal_id already exists in the Ujian table
//     $existingUjian = Ujian::where('paket_soal_id', $request->paket_soal_id)
//                           ->where('kelas_id', $request->kelas_id)
//                           ->first();

//     if ($existingUjian) {
//         return response()->json(['success' => false, 'message' => 'The selected paket soal is already associated with an ujian in this class.'], 422);
//     }

//     // Store the data in the database
//     $ujian = Ujian::updateOrCreate(
//         ['id' => $request->id], // This will update the existing record or create a new one if ID doesn't exist
//         $validatedData
//     );

//     // Return a success response
//     return response()->json(['success' => true, 'data' => $ujian]);
// }

public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'id' => 'nullable|exists:ujian,id',
        'nama' => 'required|max:255',
        'paket_soal_id' => 'required|exists:paket_soal,id',
        'kelas_id' => 'required|exists:kelas,id',
        'waktu_mulai' => 'required|date',
        'durasi' => 'required|integer',
        'poin_benar' => 'required|integer',
        'poin_salah' => 'required|integer',
        'poin_tidak_jawab' => 'required|integer',
        'keterangan' => 'nullable|string',
        'tampilkan_nilai' => 'nullable|boolean',
        'tampilkan_hasil' => 'nullable|boolean',
        'gunakan_token' => 'nullable|boolean',
        'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
    ]);

    // If an ID is provided, we are updating an existing record
    if ($request->id != null) {
        $existingUjian = Ujian::where('paket_soal_id', $request->paket_soal_id)
                              ->where('kelas_id', $request->kelas_id)
                              ->where('id', '!=', $request->id) // Exclude the current ID from the check
                              ->first();

        if ($existingUjian) {
            return response()->json([
                'success' => false,
                'message' => 'The selected paket soal is already associated with another ujian in this class.'
            ], 422);
        }

        // Update the existing record
        $ujian = Ujian::findOrFail($request->id);
        $ujian->update($validatedData);
    } else {
        // Check if the combination of paket_soal_id and kelas_id already exists
        $existingUjian = Ujian::where('paket_soal_id', $request->paket_soal_id)
                              ->where('kelas_id', $request->kelas_id)
                              ->first();

        if ($existingUjian) {
            return response()->json([
                'success' => false,
                'message' => 'The selected paket soal is already associated with an ujian in this class.'
            ], 422);
        }

        // Create a new record
        $ujian = Ujian::create($validatedData);
    }

    // Return a success response
    return response()->json(['success' => true, 'data' => $ujian]);
}



    public function showHasilUjian($id) {
        $hasilUjian = HasilUjian::with('ujian')->find($id);
        return view('ujians.hasil-ujian', ['hasilUjian' => $hasilUjian]);
    }

    public function fetchHistory()
    {
        $histories = DB::table('ujian_histories')
            ->join('siswas', 'ujian_histories.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select(
                'siswas.nis as siswa_nis',
                'siswas.nama as siswa_name', // Add student's name
                'kelas.name as kelas_name',
                'ujian_histories.created_at as created_at',
                'ujian_histories.jumlah_benar',
                'ujian_histories.jumlah_salah',
                'ujian_histories.total_nilai'
            )
            ->get();

        return response()->json($histories);
    }

    public function end(Request $request){

        $ujianId = $request->input('ujian_id');
        $answeredQuestions = $request->input('answeredQuestions');

        // Logika untuk menghitung hasil ujian
        $hasilUjian = $this->calculateExamResult($ujianId, $answeredQuestions,$request->user_id);
        // Simpan hasil ujian ke database
        $hasilUjian->save();

        return response()->json([
            'success' => true,
            'hasil_ujian_id' => $hasilUjian->id
        ]);
    }

    private function calculateExamResult($ujianId, $answeredQuestions, $siswa_id)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $totalQuestions = $ujian->paketSoal->soals->count();
        $correctAnswers = 0;
        $wrongAnswers = 0;

        // Fetch the siswa_id based on user ID
        $siswa = User::where('users.id', $siswa_id)
            ->join('siswas', 'siswas.nis', '=', 'users.username')
            ->select('siswas.id as siswa_id')
            ->first();

        if (!$siswa) {
            throw new \Exception('Siswa not found',$siswa_id);
        }

        $siswa_id = $siswa->siswa_id;

        // Fetch all questions for the given paket_soal_id
        $questions = Soal::where('paket_soal_id', $ujian->paket_soal_id)->get();
        foreach ($answeredQuestions as $questionId => $userAnswer) {
            // Find the specific question by its ID in the fetched questions
            $soal = $questions[$questionId];

            // Ensure the question exists
            if (!$soal) continue;

            // Convert answers to lowercase for a case-insensitive comparison
            $userAnswer = strtolower(trim($userAnswer));
            $correctAnswer = strtolower(trim($soal->jawaban_benar));
            // Check if the user's answer is correct, not answered, or wrong
            if (empty($userAnswer)) {
                $status = 'not answered'; // The user did not provide an answer
            } elseif ($userAnswer === $correctAnswer) {
                $correctAnswers++;
                $status = 'correct'; // Exact match
            } elseif (strpos($userAnswer, $correctAnswer) !== false) {
                $correctAnswers++;
                $status = 'correct'; // Partial match
            } else {
                // Similarity check (e.g., using similar_text)
                similar_text($userAnswer, $correctAnswer, $percent);
                if ($percent > 80) { // Adjust threshold as needed
                    $correctAnswers++;
                    $status = 'correct'; // Similar enough
                } else {
                    $wrongAnswers++;
                    $status = 'wrong'; // The user's answer is wrong
                }
            }

            // Store each answer in the database
            DB::table('ujian_histories_details')->insert([
                'ujian_history_id' => $ujianId,
                'soal_id' => $soal->id,
                'jawaban_siswa' => $userAnswer,
                'jawaban_benar' => $correctAnswer,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Calculate the score
        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

        // Create the exam result record
        $hasilUjian = new HasilUjian();
        $hasilUjian->ujian_id = $ujianId;
        $hasilUjian->jumlah_benar = $correctAnswers;
        $hasilUjian->jumlah_salah = $wrongAnswers;
        $hasilUjian->nilai = $score;
        $hasilUjian->save();

        // Insert or update the ujian_histories table
        DB::table('ujian_histories')->insert([
            'ujian_id' => $ujianId,
            'siswa_id' => $siswa_id, // Use the correct integer value
            'paket_soal_id' => $ujian->paket_soal_id,
            'jumlah_benar' => $correctAnswers,
            'jumlah_salah' => $wrongAnswers,
            'total_nilai' => $score,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $hasilUjian;
    }




    // private function calculateExamResult($ujianId, $answeredQuestions)
    // {
    //     $ujian = Ujian::findOrFail($ujianId);
    //     $totalQuestions = $ujian->paketSoal->soals->count();
    //     $correctAnswers = 0;

    //     foreach ($answeredQuestions as $questionId => $answer) {
    //         $soal = Soal::find($questionId + 1);

    //         // Ensure the question exists
    //         if (!$soal) continue;

    //         // Convert answers to lowercase
    //         $userAnswer = strtolower($answer);
    //         $correctAnswer = strtolower($soal->jawaban_benar);

    //         if ($correctAnswer === $userAnswer) {
    //             $correctAnswers++;
    //         }

    //         // Store each answer in the database
    //         DB::table('ujian_histories_details')->insert([
    //             'ujian_history_id' => $ujianId,
    //             'soal_id' => $soal->id,
    //             'jawaban_siswa' => $userAnswer,
    //             'jawaban_benar' => $correctAnswer,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     $score = ($correctAnswers / $totalQuestions) * 100;

    //     // Create the exam result record
    //     $hasilUjian = new HasilUjian();
    //     $hasilUjian->ujian_id = $ujianId;
    //     $hasilUjian->jumlah_benar = $correctAnswers;
    //     $hasilUjian->jumlah_salah = $totalQuestions - $correctAnswers;
    //     $hasilUjian->nilai = $score;

    //     return $hasilUjian;
    // }
    public function show(Request $request)
    {
        $ujian = Ujian::with(['paketSoal.soals'])->where('id',$request->ujian)->first();
        return view('ujians.show', compact('ujian'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'paket_soal_id' => 'required|exists:paket_soal,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'waktu_mulai' => 'required|date',
            'durasi' => 'required|integer',
            'tampil_hasil' => 'nullable|integer',
            'detail_hasil' => 'nullable|integer',
            'token' => 'nullable|string|max:255',
        ]);

        $ujian = Ujian::find($id);
        $ujian->update($data);

        return response()->json(['success' => 'Ujian updated successfully.']);
    }

    public function destroy($id)
    {
        Ujian::destroy($id);
        return response()->json(['success' => 'Ujian deleted successfully.']);
    }
}
