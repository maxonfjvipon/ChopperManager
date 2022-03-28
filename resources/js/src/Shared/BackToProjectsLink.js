import React from 'react'
import {Link} from "@inertiajs/inertia-react";

export const BackToProjectsLink = () => {
    return <Link href={route('projects.index')}>{"<<Назад к проектам"}</Link>
}
