<?php

namespace App\Imports;

use App\Medicine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MedicinesImport implements WithValidation, SkipsOnFailure, ToCollection, WithHeadingRow
{
    use Importable, SkipsFailures;
    // public function model(array $row)
    // {return new Medicine([  ]); }

    private $errors = []; // array to accumulate errors

    
    public function collection(Collection $rows) {
        $rows = $rows->toArray();

        // iterating each row and validating it:
        foreach ($rows as $key=>$row) {
            $validator = Validator::make($row, $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        // accumulating errors:
                        $this->errors[] = $error;
                    }
                }
            } else {
                $medicine = new Medicine();
                $medicine->med_name = $row['med_name'];
                $medicine->generic_name = $row['generic_name'];
                $medicine->medicinecategory_id = 1;
                $medicine->manufacturer_id = 1;
                $medicine->sell_price = $row['sell_price'];
                $medicine->note = 'good!';
                $medicine->save();
            }
        }
    }

    // this function returns all validation errors after import
    public function getErrors() {
        return $this->errors;
    }

   public function rules(): array {
       return [
           'med_name' => 'required|string|unique:medicines,med_name',
           'generic_name' => 'required',
           'sell_price' => 'required',
       ];
   }

   public function validationMessages() {
       return [
           'med_name.required' => 'Medicine name is required',
           'generic_name.required' => 'Generic name is required',
           'sell_price.required' => 'Sale price is required',
       ];
   }
}
