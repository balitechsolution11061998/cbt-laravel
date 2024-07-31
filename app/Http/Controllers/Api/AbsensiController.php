<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $niks = $request->input('nik'); // assume you're passing an array of nik values
            if (!$niks) {
                throw new \Exception('Nik values are required');
            }
            $absensi = Absensi::where('nik', $niks)->get();
            return response()->json($absensi);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $absensi = new Absensi();
            $absensi->nik = $request->input('nik');
            $absensi->tgl_presensi = $request->input('tgl_presensi');
            $absensi->jam_in = $request->input('jam_in');
            $absensi->jam_out = $request->input('jam_out');
            $absensi->lokasi_in = $request->input('lokasi_in');
            $absensi->lokasi_out = $request->input('lokasi_out');
            $absensi->kode_jam_kerja = $request->input('kode_jam_kerja');
            $absensi->status = $request->input('status');
            $absensi->kode_izin = $request->input('kode_izin');

            if ($request->hasFile('foto_in')) {
                $file = $request->file('foto_in');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/absensi/foto_in', $filename);
                $absensi->foto_in = $filename;
            }

            if ($request->hasFile('foto_out')) {
                $file = $request->file('foto_out');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/absensi/foto_out', $filename);
                $absensi->foto_out = $filename;
            }

            $absensi->save();
            return response()->json($absensi, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json($absensi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $absensi->nik = $request->input('nik');
        $absensi->tgl_presensi = $request->input('tgl_presensi');
        $absensi->jam_in = $request->input('jam_in');
        $absensi->jam_out = $request->input('jam_out');
        $absensi->foto_in = $request->input('foto_in');
        $absensi->foto_out = $request->input('foto_out');
        $absensi->lokasi_in = $request->input('lokasi_in');
        $absensi->lokasi_out = $request->input('lokasi_out');
        $absensi->kode_jam_kerja = $request->input('kode_jam_kerja');
        $absensi->status = $request->input('status');
        $absensi->kode_izin = $request->input('kode_izin');
        $absensi->save();
        return response()->json($absensi);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $absensi->delete();
        return response()->json(null, 204);
    }
}
