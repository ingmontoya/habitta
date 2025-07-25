<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConjuntoConfig extends Model
{
    protected $fillable = [
        'name',
        'description',
        'number_of_towers',
        'floors_per_tower',
        'apartments_per_floor',
        'is_active',
        'tower_names',
        'configuration_metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tower_names' => 'array',
        'configuration_metadata' => 'array',
    ];

    public function apartmentTypes(): HasMany
    {
        return $this->hasMany(ApartmentType::class);
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }

    public function paymentConcepts(): HasMany
    {
        return $this->hasMany(PaymentConcept::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getEstimatedApartmentsCountAttribute(): int
    {
        return $this->number_of_towers * $this->floors_per_tower * $this->apartments_per_floor;
    }

    public function getTowerNamesListAttribute(): array
    {
        if ($this->tower_names && is_array($this->tower_names)) {
            return $this->tower_names;
        }

        // Generate default tower names (1, 2, 3, ...)
        return range(1, $this->number_of_towers);
    }

    public function canGenerateApartments(): bool
    {
        return $this->apartmentTypes()->count() > 0;
    }

    public function generateApartments(): void
    {
        if (! $this->canGenerateApartments()) {
            throw new \Exception('No se pueden generar apartamentos sin tipos de apartamento definidos.');
        }

        $apartmentTypes = $this->apartmentTypes;
        $towerNames = $this->tower_names_list;

        for ($towerIndex = 0; $towerIndex < $this->number_of_towers; $towerIndex++) {
            $towerName = $towerNames[$towerIndex] ?? ($towerIndex + 1);

            for ($floor = 1; $floor <= $this->floors_per_tower; $floor++) {
                for ($position = 1; $position <= $this->apartments_per_floor; $position++) {
                    $apartmentNumber = $towerName.$floor.sprintf('%02d', $position);

                    // Select apartment type based on position or random
                    $apartmentType = $apartmentTypes->random();

                    // Check if apartment already exists
                    $existingApartment = $this->apartments()
                        ->where('number', $apartmentNumber)
                        ->where('tower', $towerName)
                        ->first();

                    if (! $existingApartment) {
                        $this->apartments()->create([
                            'apartment_type_id' => $apartmentType->id,
                            'number' => $apartmentNumber,
                            'tower' => (string) $towerName,
                            'floor' => $floor,
                            'position_on_floor' => $position,
                            'status' => 'Available',
                            'monthly_fee' => $apartmentType->administration_fee,
                            'utilities' => [],
                            'features' => [],
                        ]);
                    }
                }
            }
        }
    }
}
