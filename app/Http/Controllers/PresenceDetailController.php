<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\PresenceDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\DataTables\PresenceDetailsDataTable;

class PresenceDetailController extends Controller
{
    public function exportPdf(string $id)
    {
        $presence = Presence::findOrFail($id);
        $presenceDetails = PresenceDetail::where('presence_id', $id)->get();
        
        // load view to pdf
        $pdf = Pdf::setOption(['isRemoteEnabled' => true])
            ->loadView('pages.presence.detail.export-pdf', compact('presence', 'presenceDetails'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("{$presence->nama_kegiatan}.pdf", ['Attachment' => 0]); //Perbaikan pada array

    }

public function data(PresenceDetailsDataTable $dataTable, $presence)
{
    // Pass the model, NOT the query builder
    return $dataTable->query(new PresenceDetail())->where('presence_id', $presence)->toJson(); 

    // OR, if you want to use dependency injection:
    //return $dataTable->with('presence_id',$presence)->toJson();

}
    public function destroy($id)
    {
        $presenceDetail = PresenceDetail::findOrFail($id);

        if ($presenceDetail->tanda_tangan) {
            Storage::disk('public_uploads')->delete($presenceDetail->tanda_tangan);
        }

        $presenceDetail->delete();

        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }
}
