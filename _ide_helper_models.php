<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\ConnectionType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionType whereName($value)
 */
	class ConnectionType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CurrentPhase
 *
 * @property int $id
 * @property int $value
 * @property int $voltage
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentPhase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentPhase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentPhase query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentPhase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentPhase whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrentPhase whereVoltage($value)
 */
	class CurrentPhase extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DN
 *
 * @property int $id
 * @property int $value
 * @method static \Illuminate\Database\Eloquent\Builder|DN newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DN newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DN query()
 * @method static \Illuminate\Database\Eloquent\Builder|DN whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DN whereValue($value)
 */
	class DN extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LimitCondition
 *
 * @property int $id
 * @property string $sign
 * @method static \Illuminate\Database\Eloquent\Builder|LimitCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LimitCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LimitCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|LimitCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LimitCondition whereSign($value)
 */
	class LimitCondition extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string $creation_date
 * @property int $selections_count
 * @property int $user_id
 * @property int $deleted
 * @property-read \App\Models\users\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSelectionsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UsersAndPumpProducersAndDiscounts
 *
 * @property int $id
 * @property int $user_id
 * @property int $pump_producer_id
 * @property float $discount
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts query()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts wherePumpProducerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersAndPumpProducersAndDiscounts whereUserId($value)
 */
	class UsersAndPumpProducersAndDiscounts extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\Pump
 *
 * @property int $id
 * @property string $part_num_main
 * @property string|null $part_num_backup
 * @property string|null $part_num_archive
 * @property int $series_id
 * @property string $name
 * @property float $price
 * @property int $currency_id
 * @property float $weight
 * @property float $power
 * @property float $amperage
 * @property int $connection_type_id
 * @property float $min_liquid_temp
 * @property float $max_liquid_temp
 * @property int $between_axes_dist
 * @property int $dn_input_id
 * @property int $dn_output_id
 * @property int $category_id
 * @property int $phase_id
 * @property string|null $performance
 * @property int $regulation_id
 * @method static \Illuminate\Database\Eloquent\Builder|Pump newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pump newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pump query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereAmperage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereBetweenAxesDist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereConnectionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereDnInputId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereDnOutputId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereMaxLiquidTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereMinLiquidTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePartNumArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePartNumBackup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePartNumMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePerformance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePhaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereRegulationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pump whereWeight($value)
 */
	class Pump extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpAndApplication
 *
 * @property int $id
 * @property int $pump_id
 * @property int $application_id
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndApplication whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndApplication wherePumpId($value)
 */
	class PumpAndApplication extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpAndType
 *
 * @property int $id
 * @property int $pump_id
 * @property int $type_id
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndType wherePumpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpAndType whereTypeId($value)
 */
	class PumpAndType extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpApplication
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PumpApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpApplication whereName($value)
 */
	class PumpApplication extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpCategory
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PumpCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpCategory whereName($value)
 */
	class PumpCategory extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpProducer
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PumpProducer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpProducer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpProducer query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpProducer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpProducer whereName($value)
 */
	class PumpProducer extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpRegulation
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PumpRegulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpRegulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpRegulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpRegulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpRegulation whereName($value)
 */
	class PumpRegulation extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpSeries
 *
 * @property int $id
 * @property string $name
 * @property int $producer_id
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeries whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeries whereProducerId($value)
 */
	class PumpSeries extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpSeriesAndRegulation
 *
 * @property int $id
 * @property int $series_id
 * @property int $regulation_id
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndRegulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndRegulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndRegulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndRegulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndRegulation whereRegulationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndRegulation whereSeriesId($value)
 */
	class PumpSeriesAndRegulation extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpSeriesAndType
 *
 * @property int $id
 * @property int $series_id
 * @property int $type_id
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndType whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesAndType whereTypeId($value)
 */
	class PumpSeriesAndType extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpSeriesTemperatures
 *
 * @property int $id
 * @property int $series_id
 * @property float $temp_min
 * @property float $temp_max
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures whereTempMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSeriesTemperatures whereTempMin($value)
 */
	class PumpSeriesTemperatures extends \Eloquent {}
}

namespace App\Models\pumps{
/**
 * App\Models\pumps\PumpType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PumpType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpType whereName($value)
 */
	class PumpType extends \Eloquent {}
}

namespace App\Models\selections{
/**
 * App\Models\selections\PumpSelectionType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSelectionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSelectionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSelectionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSelectionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PumpSelectionType whereName($value)
 */
	class PumpSelectionType extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelection
 *
 * @property int $id
 * @property string $creation_date
 * @property string $update_date
 * @property int $project_id
 * @property float $pressure
 * @property float $consumption
 * @property float $liquid_temperature
 * @property float $limit
 * @property int $backup_pumps_count
 * @property int|null $power_limit_condition_id
 * @property int|null $power_limit_value
 * @property int|null $between_axes_limit_condition_id
 * @property int|null $between_axes_limit_value
 * @property int|null $dn_input_limit_condition_id
 * @property int|null $dn_input_limit_value_id
 * @property int|null $dn_output_limit_condition_id
 * @property int|null $dn_output_limit_value_id
 * @property int $deleted
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereBackupPumpsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereBetweenAxesLimitConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereBetweenAxesLimitValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereConsumption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereCreationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereDnInputLimitConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereDnInputLimitValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereDnOutputLimitConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereDnOutputLimitValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereLiquidTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection wherePowerLimitConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection wherePowerLimitValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection wherePressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelection whereUpdateDate($value)
 */
	class SinglePumpSelection extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelectionAndConnectionType
 *
 * @property int $id
 * @property int $selection_id
 * @property int $connection_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndConnectionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndConnectionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndConnectionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndConnectionType whereConnectionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndConnectionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndConnectionType whereSelectionId($value)
 */
	class SinglePumpSelectionAndConnectionType extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelectionAndCurrentPhase
 *
 * @property int $id
 * @property int $selection_id
 * @property int $phase_id
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndCurrentPhase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndCurrentPhase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndCurrentPhase query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndCurrentPhase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndCurrentPhase wherePhaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndCurrentPhase whereSelectionId($value)
 */
	class SinglePumpSelectionAndCurrentPhase extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelectionAndMainPumpsCount
 *
 * @property int $id
 * @property int $selection_id
 * @property int $count
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndMainPumpsCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndMainPumpsCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndMainPumpsCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndMainPumpsCount whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndMainPumpsCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndMainPumpsCount whereSelectionId($value)
 */
	class SinglePumpSelectionAndMainPumpsCount extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelectionAndPumpProducer
 *
 * @property int $id
 * @property int $selection_id
 * @property int $producer_id
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpProducer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpProducer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpProducer query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpProducer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpProducer whereProducerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpProducer whereSelectionId($value)
 */
	class SinglePumpSelectionAndPumpProducer extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelectionAndPumpRegulation
 *
 * @property int $id
 * @property int $selection_id
 * @property int $regulation_id
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpRegulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpRegulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpRegulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpRegulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpRegulation whereRegulationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpRegulation whereSelectionId($value)
 */
	class SinglePumpSelectionAndPumpRegulation extends \Eloquent {}
}

namespace App\Models\selections\single{
/**
 * App\Models\selections\single\SinglePumpSelectionAndPumpType
 *
 * @property int $id
 * @property int $selection_id
 * @property int $type_id
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpType query()
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpType whereSelectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SinglePumpSelectionAndPumpType whereTypeId($value)
 */
	class SinglePumpSelectionAndPumpType extends \Eloquent {}
}

namespace App\Models\users{
/**
 * App\Models\users\Area
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\users\City[] $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereName($value)
 */
	class Area extends \Eloquent {}
}

namespace App\Models\users{
/**
 * App\Models\users\Business
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\users\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Business newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business query()
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereName($value)
 */
	class Business extends \Eloquent {}
}

namespace App\Models\users{
/**
 * App\Models\users\City
 *
 * @property int $id
 * @property string $name
 * @property int $area_id
 * @property-read \App\Models\users\Area $area
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\users\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models\users{
/**
 * App\Models\users\HasUsersModel
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\users\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|HasUsersModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HasUsersModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HasUsersModel query()
 */
	class HasUsersModel extends \Eloquent {}
}

namespace App\Models\users{
/**
 * App\Models\users\Role
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\users\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models\users{
/**
 * App\Models\users\User
 *
 * @property int $id
 * @property string $name
 * @property string $inn
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $phone
 * @property string|null $fio
 * @property int $business_id
 * @property int $role_id
 * @property int $city_id
 * @property string $created_at
 * @property int $deleted
 * @property-read \App\Models\users\Business $business
 * @property-read \App\Models\users\City $city
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \App\Models\users\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 */
	class User extends \Eloquent {}
}

