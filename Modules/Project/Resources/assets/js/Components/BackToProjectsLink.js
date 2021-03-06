import React from 'react'
import {Link} from "@inertiajs/inertia-react";
import Lang from "../../../../../../resources/js/translation/lang";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";

export const BackToProjectsLink = () => {
    const tRoute = useTransRoutes()
    return <Link href={tRoute('projects.index')}>{"<<" + Lang.get('pages.projects.back')}</Link>
}
