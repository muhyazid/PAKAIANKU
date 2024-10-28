<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManufacturingOrder extends Model
{
    use HasFactory;
     protected $fillable = ['product_id', 'quantity', 'start_date', 'kode_MO', 'status'];

     // Tambahkan konstanta untuk status
    const STATUS_DRAFT = 'Draft';
    const STATUS_PRODUCTION = 'Production';
    const STATUS_DONE = 'Done';
    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'manufacturing_order_materials')
                    ->withPivot('to_consume', 'quantity')
                    ->withTimestamps();
    }

    public function checkMaterialStock()
    {
        $insufficientMaterials = [];
        $sufficientMaterials = [];
        
        foreach ($this->materials as $material) {
            $required = $material->pivot->to_consume;
            $available = $material->kuantitas;
            
            if ($available < $required) {
                $insufficientMaterials[] = [
                    'material' => $material,
                    'required' => $required,
                    'available' => $available,
                    'shortage' => $required - $available
                ];
            } else {
                $sufficientMaterials[] = [
                    'material' => $material,
                    'required' => $required,
                    'available' => $available
                ];
            }
        }
        
        return [
            'has_sufficient_stock' => empty($insufficientMaterials),
            'insufficient_materials' => $insufficientMaterials,
            'sufficient_materials' => $sufficientMaterials
        ];
    }

    // Method untuk memulai produksi
    public function startProduction()
    {
        $stockCheck = $this->checkMaterialStock();
        
        if (!$stockCheck['has_sufficient_stock']) {
            throw new \Exception('Insufficient stock to start production');
        }
        
        DB::beginTransaction();
        try {
            // Update status MO
            $this->status = self::STATUS_PRODUCTION;
            $this->save();
            
            // Kurangi stock material
            foreach ($this->materials as $material) {
                $material->kuantitas -= $material->pivot->to_consume;
                $material->save();
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
