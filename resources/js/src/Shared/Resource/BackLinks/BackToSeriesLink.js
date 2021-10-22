import React from 'react'
import {Link} from "@inertiajs/inertia-react";
import Lang from "../../../../translation/lang";
import {useTransRoutes} from "../../../Hooks/routes.hook";

export const BackToSeriesLink = () => {
    const {tRoute} = useTransRoutes()

    return <Link href={tRoute('pump_series.index')}>{"<<" + Lang.get('pages.pump_series.back')}</Link>
}
