import React from 'react'
import {Link} from "@inertiajs/inertia-react";
import Lang from "../../../../translation/lang";
import {useTransRoutes} from "../../../Hooks/routes.hook";

export const BackToUsersLink = () => {
    const {tRoute} = useTransRoutes()

    return <Link href={tRoute('users.index')}>{"<<" + Lang.get('pages.users.back')}</Link>
}
