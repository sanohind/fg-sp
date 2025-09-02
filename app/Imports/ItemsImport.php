<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Log;

class ItemsImport implements ToModel, WithHeadingRow, WithStartRow
{
    private $successCount = 0;
    private $errorCount = 0;
    private $errors = [];

    /**
     * Start reading from row 9 (index 8)
     */
    public function startRow(): int
    {
        return 9;
    }

    /**
     * Map Excel columns to model attributes
     */
    public function model(array $row)
    {
        try {
            // Debug: Log the entire row data
            Log::info('=== EXCEL ROW DATA ===');
            Log::info('Raw row data:', $row);
            Log::info('Row keys:', array_keys($row));
            
            // Map Excel columns using numeric index
            // Excel columns: A=0, B=1, C=2, D=3, E=4, F=5, G=6
            // A=NO, B=ERP CODE, C=PART NO, D=DESCRIPTION, E=MODEL, F=CUSTOMER, G=QTY
            $erpCode = $row[1] ?? null;        // Column B (ERP CODE)
            $partNo = $row[2] ?? null;         // Column C (PART NO)
            $description = $row[3] ?? null;    // Column D (DESCRIPTION)
            $model = $row[4] ?? null;          // Column E (MODEL)
            $customer = $row[5] ?? null;       // Column F (CUSTOMER)
            $qty = $row[6] ?? null;            // Column G (QTY)

            Log::info('Mapped values by index:', [
                'erp_code (col 1)' => $erpCode,
                'part_no (col 2)' => $partNo,
                'description (col 3)' => $description,
                'model (col 4)' => $model,
                'customer (col 5)' => $customer,
                'qty (col 6)' => $qty
            ]);

            // Skip empty rows
            if (empty($erpCode) || empty($partNo) || empty($description) || empty($qty)) {
                Log::info('Skipping empty row - missing required fields');
                return null;
            }

            // Check if ERP code already exists
            if (Item::where('erp_code', trim($erpCode))->exists()) {
                $this->errorCount++;
                $this->errors[] = "Row " . ($this->successCount + $this->errorCount) . ": ERP Code '" . trim($erpCode) . "' sudah ada dalam sistem";
                Log::warning('Duplicate ERP code found', ['erp_code' => trim($erpCode)]);
                return null;
            }

            // Create new item
            $itemData = [
                'erp_code' => trim($erpCode),
                'part_no' => trim($partNo),
                'description' => trim($description),
                'model' => !empty($model) ? trim($model) : null,
                'customer' => !empty($customer) ? trim($customer) : null,
                'qty' => (int) $qty,
                'part_img' => null,        // Default value for image fields
                'packaging_img' => null,   // Default value for image fields
            ];

            Log::info('Creating item with data:', $itemData);

            $item = new Item($itemData);
            $this->successCount++;
            
            Log::info('Item created successfully!', ['erp_code' => $itemData['erp_code'], 'id' => $item->id]);
            return $item;

        } catch (\Exception $e) {
            $this->errorCount++;
            $this->errors[] = "Row " . ($this->successCount + $this->errorCount) . ": " . $e->getMessage();
            Log::error('Excel import error', [
                'row' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Get import statistics
     */
    public function getStatistics(): array
    {
        return [
            'success_count' => $this->successCount,
            'error_count' => $this->errorCount,
            'errors' => $this->errors
        ];
    }
}
