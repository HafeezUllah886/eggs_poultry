<?php
// filepath: /C:/laragon/www/auction/app/Exports/PurchasesExport.php
namespace App\Exports;

use App\Models\purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchasesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return Purchase::whereBetween('purchases.date', [$this->start, $this->end])
            ->leftJoin('accounts', 'purchases.transporter_id', '=', 'accounts.id')
            ->get([
                'accounts.title as transporter_title',
                'purchases.date',
                'purchases.auction',
                'purchases.category',
                'purchases.loot',
                'purchases.chassis',
                'purchases.maker',
                'purchases.model',
                'purchases.year',
                'purchases.price',
                'purchases.ptax',
                'purchases.afee',
                'purchases.atax',
                'purchases.transport_charges',
                'purchases.recycle',
                'purchases.total',
                'purchases.yard',
                'purchases.ddate',
                'purchases.adate',
                'purchases.number_plate',
                'purchases.nvalidity',
                'purchases.notes',
                
            ]);
    }

    public function headings(): array
    {
        return [
            'transporter',
            'purchase_date',
            'auction',
            'category',
            'lot_no',
            'chassis_no',
            'maker',
            'model',
            'year',
            'price',
            'purchase_tax',
            'auction_fee',
            'auction_tax',
            'transport_charges',
            'recycle',
            'total',
            'yard',
            'document_date',
            'arrival_date',
            'number_plate',
            'number_validity',
            'notes'
        ];
    }
}
