<?php

namespace Modules\Pump\Entities;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Selection\Support\Regression\EqPolynomial;

/**
 * Pump coefficients
 * @method static create(array $coefficientsToCreate)
 * @method static where(string $string, mixed $id)
 * @property mixed $b
 * @property mixed $k
 * @property mixed $c
 */
final class PumpCoefficients extends Model
{
    use HasFactory;

    protected $table = "pump_coefficients";
    protected $fillable = ['pump_id', 'position', 'k', 'b', 'c'];
    public $timestamps = false;

    /**
     * @throws Exception
     */
    public static function createdForPumpAtPosition(Pump $pump, int $position): self
    {
        $eq = EqPolynomial::new(
            $pump->performance()->asArrayAt($position)
        )->asArray();
        return self::create([
            'pump_id' => $pump->id,
            'position' => $position,
            'k' => $eq[0],
            'b' => $eq[1],
            'c' => $eq[2]
        ]);
    }
}
