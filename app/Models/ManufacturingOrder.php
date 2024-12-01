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
    const STATUS_CONFIRMED = 'Confirmed';
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

  // Model: ManufacturingOrder.php

    public function checkMaterialStock()
    {
        $bom = BoM::where('product_id', $this->product_id)->with('materials')->first();

        if (!$bom) {
            throw new \Exception('BoM tidak ditemukan untuk produk ini.');
        }

        $insufficientMaterials = [];
        $sufficientMaterials = [];

        foreach ($bom->materials as $material) {
            $required = $material->pivot->quantity * $this->quantity;
            $available = $material->stock;

            if ($available < $required) {
                $insufficientMaterials[] = [
                    'material' => $material,
                    'required' => $required,
                    'available' => $available,
                    'shortage' => $required - $available,
                ];
            } else {
                $sufficientMaterials[] = [
                    'material' => $material,
                    'required' => $required,
                    'available' => $available,
                ];
            }
        }

        return [
            'has_sufficient_stock' => empty($insufficientMaterials),
            'sufficient_materials' => $sufficientMaterials,
            'insufficient_materials' => $insufficientMaterials,
        ];
    }



    // Method untuk memulai produksi
    public function startProduction()
    {
        DB::beginTransaction();
        try {
            $stockStatus = $this->checkMaterialStock();
            
            if (!$stockStatus['has_sufficient_stock']) {
                throw new \Exception('Stok material tidak mencukupi untuk produksi');
            }

            // Get BOM for the product
            $bom = BoM::where('product_id', $this->product_id)->latest()->first();
            
            if (!$bom) {
                throw new \Exception('BOM tidak ditemukan');
            }

            // Kurangi stok material berdasarkan BOM
            foreach ($bom->materials as $bomMaterial) {
                $material = Material::find($bomMaterial->id);
                $requiredQuantity = $bomMaterial->pivot->quantity * $this->quantity;
                
                if ($material->kuantitas < $requiredQuantity) {
                    throw new \Exception("Stok {$material->nama_bahan} tidak mencukupi");
                }
                
                $material->kuantitas -= $requiredQuantity;
                $material->save();
                
                // Attach material to manufacturing order
                $this->materials()->attach($material->id, [
                    'to_consume' => $requiredQuantity,
                    'quantity' => $requiredQuantity
                ]);
            }

            // Update status MO
            $this->status = self::STATUS_CONFIRMED;
            $this->save();

            // Tambah stok produk
            $this->product->stock += $this->quantity;
            $this->product->save();
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function completeProduction()
    {
        if ($this->status !== self::STATUS_CONFIRMED) {
            throw new \Exception('Manufacturing Order harus dalam status Confirmed');
        }

        DB::beginTransaction();
        try {
            // Update status
            $this->status = self::STATUS_DONE;
            $this->save();
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
