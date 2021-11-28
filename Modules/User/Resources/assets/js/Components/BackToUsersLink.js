import React from 'react'
import {Link} from "@inertiajs/inertia-react";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import Lang from "../../../../../../resources/js/translation/lang";

export const BackToUsersLink = () => {
    const tRoute = useTransRoutes()

    return <Link href={tRoute('users.index')}>{"<<" + Lang.get('pages.users.back')}</Link>
}
