import React from 'react'
import {Link} from "@inertiajs/inertia-react";
import Lang from "../../../../../../resources/js/translation/lang";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";

export const BackToSeriesLink = () => {
    return <Link href={route('pump_series.index')}>{"<<Назад к сериям"}</Link>
}
