<?php

namespace App\DataTables;

use App\Models\PresenceDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request; // Import Request
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\URL; // Import URL

class PresenceDetailsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<PresenceDetail> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('waktu_absen', function ($query) {
                return date('d-m-Y H:i:s', strtotime($query->created_at));
            })
            ->addColumn('tanda_tangan', function ($query) {
                if ($query->tanda_tangan) {
                    $imageUrl = URL::to('uploads/' . $query->tanda_tangan); // Use URL::to()
                    return "<img src='" . $imageUrl . "' width='100'>"; // set width
                }
                return '';
            })
            ->addColumn('action', function ($query) {
                $btnDelete = '<a href="' . route('hapus_detail', $query->id) . '" class="btn btn-danger btn-sm btn-delete">Delete</a>';
                return "{$btnDelete}";
            })
            ->rawColumns(['tanda_tangan', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<PresenceDetail>
     */
    public function query(PresenceDetail $model): QueryBuilder
    {
        $presenceId = request()->route('presence');
        return $model->where('presence_id', $presenceId)->newQuery();
    }

    /**p
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('presencedetails-table')
            ->columns($this->getColumns())
            ->ajax([
                'url' => route('presence.show', request()->route('presence')), // route bawaan, otomatis HTTPS
                'type' => 'GET',
            ])
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->title('#')
                ->render('meta.row + meta.settings._iDisplayStart + 1;')
                ->width(100),
            Column::make('waktu_absen')->title('Waktu Absen'),
            Column::make('nama')->title('Nama'),
            Column::make('jabatan')->title('Jabatan'),
            Column::make('asal_instansi')->title('Asal Instansi'),
            Column::make('tanda_tangan')->title('Tanda Tangan'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')->title('Aksi'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PresenceDetails_' . date('YmdHis');
    }

    protected function presenceSlug(): string
    {
        return request()->segment(2);
    }
}
