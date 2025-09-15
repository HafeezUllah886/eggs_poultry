<?php
// filepath: /C:/laragon/www/auction/app/Imports/PurchasesImport.php
namespace App\Imports;

use App\Models\accounts;
use App\Models\purchase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class PurchasesImport implements ToModel, WithHeadingRow, WithEvents
{
    use RegistersEventListeners;

    private $rowCount = 0;
    private $errors = [];

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                DB::beginTransaction();
            },
            ImportFailed::class => function(ImportFailed $event) {
                DB::rollBack();
            },
        ];
    }

    public function model(array $row)
    {
        $this->rowCount++;
        
        try {
            // Validate required fields
            // Debug: Log the row data
            Log::info('Processing row:', $row);
            
            // Trim all values to handle any whitespace issues
            $row = array_map('trim', $row);
            
            if (empty($row['chassis_no'])) {
                throw new \Exception('Chassis number is required');
            }

            $check = purchase::where('chassis', $row['chassis_no'])->first();
            if ($check) {
                throw new \Exception('Chassis number already exists');
            }

            if (empty($row['transporter'])) {
                throw new \Exception('Transporter is required');
            }

            $transporter = accounts::where('title', $row['transporter'])->first();
            if (!$transporter) {
                throw new \Exception('Transporter not found');
            }

            $purchase = new purchase([
                'year' => $row['year'] ?? null,
                'model' => $row['model'] ?? null,
                'category' => $row['category'] ?? null,
                'maker' => $row['maker'] ?? null,
                'chassis' => $row['chassis_no'] ?? null,
                'loot' => $row['lot_no'] ?? null,
                'yard' => $row['yard'] ?? null,
                'date' => $this->transformDate($row['purchase_date'] ?? null),
                'auction' => $row['auction'] ?? null,
                'price' => $row['price'] ?? 0,
                'ptax' => $row['purchase_tax'] ?? 0,
                'afee' => $row['auction_fee'] ?? 0,
                'atax' => $row['auction_tax'] ?? 0,
                'transport_charges' => $row['transport_charges'] ?? 0,
                'total' => $row['total'] ?? 0,
                'recycle' => $row['recycle'] ?? 0,
                'adate' => $this->transformDate($row['arrival_date'] ?? null),
                'ddate' => $this->transformDate($row['document_date'] ?? null),
                'number_plate' => $row['number_plate'] ?? null,
                'nvalidity' => $row['number_plate_validity'] ?? null,
                'notes' => $row['notes'] ?? null,
                'refID' => getRef(),
                'transporter_id' => $transporter->id,
            ]);

            // Save the model to trigger any model events
            $purchase->save();
            
            // Rollback the save so the transaction can be committed all at once
            DB::commit();
            
            return $purchase;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = [
                'row' => $this->rowCount + 1, // +1 for header row
                'chassis' => $row['chassis_no'] ?? 'N/A',
                'error' => $e->getMessage(),
                'row_data' => $row // Add full row data for debugging
            ];
            
            // Log the error for debugging
            Log::error('Import error:', [
                'row' => $this->rowCount + 1,
                'error' => $e->getMessage(),
                'row_data' => $row
            ]);
            
            if (count($this->errors) >= 10) { // Limit number of errors to prevent memory issues
                throw new \Exception('Too many errors. Please fix the file and try again.');
            }
            
            return null;
        }
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorCount()
    {
        return count($this->errors);
    }

    private function transformDate($date)
    {
        try {
            if (empty($date)) {
                return null;
            }
            
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->format('Y-m-d');
            }
            
            // Handle dd-mm-yy format
            if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $date)) {
                return Carbon::createFromFormat('d-m-y', $date)->format('Y-m-d');
            }
            
            // Handle other date formats
            return Carbon::parse($date)->format('Y-m-d');
            
        } catch (\Exception $e) {
            throw new \Exception('Invalid date format: ' . $e->getMessage() . ' (Expected dd-mm-yy)');
        }
    }
    
    public function commit()
    {
        if (empty($this->errors)) {
            DB::commit();
            return true;
        }
        
        DB::rollBack();
        return false;
    }
}
