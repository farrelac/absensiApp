<?php

namespace App\Http\Controllers;

use App\DataTables\AbsenDataTable;
use App\Models\Presence;
use App\Models\PresenceDetail; // Import the PresenceDetail model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class AbsenController extends Controller
{
    public function index($slug, AbsenDataTable $dataTable)
    {
        $presence = Presence::where('slug', $slug)->first();
        if (!$presence) {
            return abort(404);
        }

        // Ambil data detail kehadiran terkait dengan kehadiran ini
        $presenceDetails = PresenceDetail::where('presence_id', $presence->id)->get();
        return $dataTable->render('pages.absen.index', compact('presence'));
    }

    public function save(Request $request, string $id)
    {
        $presence = Presence::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'asal_instansi' => 'required',
            'signature' => 'required',
        ], [
            'nama.required' => 'Kolom nama wajib diisi!',
            'jabatan.required' => 'Kolom jabatan wajib diisi!',
            'asal_instansi.required' => 'Kolom asal instansi wajib diisi!',
            'signature.required' => 'Kolom tanda tangan wajib diisi!',
        ]);

        $presenceDetail = new PresenceDetail(); // Use the model
        $presenceDetail->presence_id = $presence->id;
        $presenceDetail->nama = $request->nama;
        $presenceDetail->jabatan = $request->jabatan;
        $presenceDetail->asal_instansi = $request->asal_instansi;

        // Decode base64 image
        $base64_image = $request->signature;
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);

        if ($file_data === null) {
            return redirect()->back()->withErrors(['signature' => 'Invalid signature data.']);
        }

        $file_data = base64_decode($file_data);

        if ($file_data === false) {
            return redirect()->back()->withErrors(['signature' => 'Failed to decode base64 signature data.']);
        }
        // Generate file name
        $uniqChar = date('YmdHis') . uniqid();
        $tanda_tangan = "tanda-tangan/{$uniqChar}.png";

        // Save image to public/uploads
        Storage::disk('public_uploads')->put($tanda_tangan, $file_data);

        $presenceDetail->tanda_tangan = $tanda_tangan;
        $presenceDetail->save();

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.'); // Consider adding a success message.
    }
}
