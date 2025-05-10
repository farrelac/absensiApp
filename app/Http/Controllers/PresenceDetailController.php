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
 public function data(PresenceDetailsDataTable $dataTable)
    {
        // The PresenceDetailsDataTable service internally uses its `query()` method
        // to get the base query (which includes the where('presence_id', ...))
        // and then processes the AJAX request parameters (filtering, sorting, pagination).
        // You simply need to tell the service to handle the request and return the JSON.
        return $dataTable->toJson();
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
