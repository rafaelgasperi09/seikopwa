<?php

namespace App\Exports;

use App\AccessLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccessLogsExport implements FromQuery, WithMapping, WithHeadings
{
    public function query()
    {
        $cu=current_user();
        $cliente_ids = explode(',', $cu->crm_clientes_id);

        return AccessLog::with('user')
                ->when($cu->isCliente(), function ($q) use ($cliente_ids) {
                    $q->whereHas('user', function ($q2) use ($cliente_ids) {
                        $q2->where(function ($subquery) use ($cliente_ids) {
                            foreach ($cliente_ids as $i => $id) {
                                $subquery->orWhereRaw("FIND_IN_SET(?, crm_clientes_id)", [$id]);
                            }
                        });
                    });
                })
                ->select('id', 'user_id', 'ip_address', 'created_at');
    }

    public function map($row): array
    {
        return [
            $row->id,
            optional($row->user)->full_name, // Aquí mostramos el nombre del usuario
            $row->ip_address,
            $row->created_at->format('Y-m-d'),
            $row->created_at->format('H:i:s'),
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Usuario', 'IP', 'Fecha','Hora'];
    }
}
