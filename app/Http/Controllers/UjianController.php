<?php

namespace App\Http\Controllers;

use App\Models\HasilUjian;
use App\Models\PaketSoal;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianHistory;
use App\Models\UjianHistoryDetail;
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




public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'nama' => 'required|max:255',
        'paket_soal_id' => 'required|exists:paket_soal,id',
        'kelas_id' => 'required',
        'waktu_mulai' => 'required|date',
        'durasi' => 'required|integer',
        'poin_benar' => 'required|integer',
        'poin_salah' => 'required|integer',
        'poin_tidak_jawab' => 'required|integer',
        'keterangan' => 'nullable|string',
        'tampilkan_nilai' => 'nullable|boolean',
        'tampilkan_hasil' => 'nullable|boolean',
        'gunakan_token' => 'nullable|boolean',
        'mata_pelajaran_id' => 'required',
    ]);

    // Check if the combination of paket_soal_id already exists in the Ujian table
    $existingUjian = Ujian::where('paket_soal_id', $request->paket_soal_id)
                          ->where('kelas_id', $request->kelas_id)
                          ->first();

    if ($existingUjian) {
        return response()->json(['success' => false, 'message' => 'The selected paket soal is already associated with an ujian in this class.'], 422);
    }

    // Store the data in the database
    $ujian = Ujian::updateOrCreate(
        ['id' => $request->id], // This will update the existing record or create a new one if ID doesn't exist
        $validatedData
    );

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
        $hasilUjian = $this->calculateExamResult($ujianId, $answeredQuestions);
        // Simpan hasil ujian ke database
        $hasilUjian->save();

        return response()->json([
            'success' => true,
            'hasil_ujian_id' => $hasilUjian->id
        ]);
    }


    private function calculateExamResult($ujianId, $answeredQuestions)
    {
        $ujian = Ujian::findOrFail($ujianId);
        $totalQuestions = $ujian->paketSoal->soals->count();
        $correctAnswers = 0;

        foreach ($answeredQuestions as $questionId => $answer) {
            $soal = Soal::find($questionId + 1);

            // Ensure the question exists
            if (!$soal) continue;

            // Convert answers to lowercase
            $userAnswer = strtolower($answer);
            $correctAnswer = strtolower($soal->jawaban_benar);

            if ($correctAnswer === $userAnswer) {
                $correctAnswers++;
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

        $score = ($correctAnswers / $totalQuestions) * 100;

        // Create the exam result record
        $hasilUjian = new HasilUjian();
        $hasilUjian->ujian_id = $ujianId;
        $hasilUjian->jumlah_benar = $correctAnswers;
        $hasilUjian->jumlah_salah = $totalQuestions - $correctAnswers;
        $hasilUjian->nilai = $score;

        return $hasilUjian;
    }
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
