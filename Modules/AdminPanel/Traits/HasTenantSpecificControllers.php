<?php


namespace Modules\AdminPanel\Traits;


trait HasTenantSpecificControllers
{
    use HasControllerInModule,
        HasClassInModule,
        HasSelectionsControllersInModule,
        HasAuthControllersInModule,
        HasUsersController,
        HasSeriesControllersInModule,
        HasPumpBrandsControllerInModule
        ;
}
