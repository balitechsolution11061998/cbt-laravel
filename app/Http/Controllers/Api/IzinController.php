<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Izin;
use App\Http\Controllers\Controller;

class IzinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $nik = $request->input('nik');

            if ($nik) {
                // Get the izin records for the given nik
                $izins = Izin::where('nik', $nik)->where('kode_izin','!=',null)->get();
                // Calculate the jumlah_izin
                $jumlah_izin = $izins->count();
            } else {
                // Get all izin records
                $izins = Izin::where('kode_izin','!=',null)->get();
                // Calculate the jumlah_izin
                $jumlah_izin = $izins->count();
            }

            // Return response with jumlah_izin
            return response()->json([
                'jumlah_izin' => $jumlah_izin,
                'data' => $izins
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving izin data: ' . $e->getMessage()], 500);
        }
    }

    public function indexCuti(Request $request)
    {
        try {
            $nik = $request->input('nik');

            if ($nik) {
                // Get the izin records for the given nik
                $cuti = Izin::where('nik', $nik)->where('kode_cuti','!=',null)->get();
                // Calculate the jumlah_izin
                $jumlah_cuti = $cuti->count();
            } else {
                // Get all izin records
                $cuti = Izin::where('kode_cuti','!=',null)->get();
                // Calculate the jumlah_izin
                $jumlah_cuti = $cuti->count();
            }

            // Return response with jumlah_izin
            return response()->json([
                'jumlah_cuti' => $jumlah_cuti,
                'data' => $cuti
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving izin data: ' . $e->getMessage()], 500);
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
        $izin = new Izin();
        $izin->kode_izin = $request->input('kode_izin');
        $izin->nik = $request->input('nik');
        $izin->tgl_izin_dari = $request->input('tgl_izin_dari');
        $izin->tgl_izin_sampai = $request->input('tgl_izin_sampai');
        $izin->status = "Progress";
        $izin->keterangan = $request->input('keterangan');
        $izin->doc_sid = $request->input('doc_sid');
        $izin->status_approved = "Progress";
        $izin->save();
        return response()->json($izin, 201);
    }

    public function storeCuti(Request $request)
    {
        $izin = new Izin();
        $izin->kode_cuti = $request->input('kode_cuti');
        $izin->nik = $request->input('nik');
        $izin->tgl_izin_dari = $request->input('tgl_izin_dari');
        $izin->tgl_izin_sampai = $request->input('tgl_izin_sampai');
        $izin->status = "Progress";
        $izin->keterangan = $request->input('keterangan');
        $izin->doc_sid = $request->input('doc_sid');
        $izin->status_approved = "Progress";
        $izin->save();
        return response()->json($izin, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $izin = Izin::find($id);
        if (!$izin) {
            return response()->json(['error' => 'Izin not found'], 404);
        }
        return response()->json($izin);
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
        $izin = Izin::find($id);
        if (!$izin) {
            return response()->json(['error' => 'Izin not found'], 404);
        }
        $izin->kode_izin = $request->input('kode_izin');
        $izin->nik = $request->input('nik');
        $izin->tgl_izin_dari = $request->input('tgl_izin_dari');
        $izin->tgl_izin_sampai = $request->input('tgl_izin_sampai');
        $izin->status = $request->input('status');
        $izin->kode_cuti = $request->input('kode_cuti');
        $izin->keterangan = $request->input('keterangan');
        $izin->doc_sid = $request->input('doc_sid');
        $izin->status_approved = $request->input('status_approved');
        $izin->save();
        return response()->json($izin);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $izin = Izin::find($id);
        if (!$izin) {
            return response()->json(['error' => 'Izin not found'], 404);
        }
        $izin->delete();
        return response()->json(['message' => 'Izin deleted successfully']);
    }
}
