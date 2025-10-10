<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobseekerCartItem;
use App\Models\TrainingBatch;
use App\Models\JobseekerTrainingMaterialPurchase;
use Carbon\Carbon;

class UpdateCartItems extends Command
{
    protected $signature = 'cart:update';
    protected $description = 'Update Jobseeker cart item prices and batch availability automatically.';

    public function handle()
    {
        $cartItems = JobseekerCartItem::with('material')->get();
        $updatedCount = 0;

        foreach ($cartItems as $item) {
            $material = $item->material;
            if (!$material) continue;

            // ✅ Update Price
            $item->price = $material->training_offer_price ?? $material->training_price ?? 0;

            // ✅ Update Batch Info
            if ($item->batch_id) {
                $batch = TrainingBatch::find($item->batch_id);
                if ($batch) {
                    $strength = $batch->strength ?? 0;
                    $enrolled = JobseekerTrainingMaterialPurchase::where('batch_id', $batch->id)
                                ->where('material_id', $item->material_id)
                                ->count();

                    $availableSeats = max($strength - $enrolled, 0);
                    $item->available_seats = $availableSeats;

                    // Update status
                    $endDate = Carbon::parse($batch->end_date ?? $batch->start_date);
                    if ($endDate->isPast()) {
                        $item->status = 'expired';
                    } elseif ($availableSeats <= 0) {
                        $item->status = 'full';
                    } else {
                        $item->status = 'active';
                    }
                } else {
                    $item->status = 'invalid_batch';
                    $item->available_seats = 0;
                }
            } else {
                $item->available_seats = null; // No batch assigned
                $item->status = 'active';
            }

            $item->save();
            $updatedCount++;
        }

        $this->info("✅ Updated {$updatedCount} cart items successfully.");
        return 0;
    }
}
