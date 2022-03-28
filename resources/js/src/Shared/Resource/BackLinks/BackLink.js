import React from 'react'
import {Link} from "@inertiajs/inertia-react";

export const BackLink = ({title, href}) => {
    return <Link href={href}>{"<<" + title}</Link>
}
