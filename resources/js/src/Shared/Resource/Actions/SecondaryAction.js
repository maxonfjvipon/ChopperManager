import React from 'react'
import {Inertia} from "@inertiajs/inertia";
import {SecondaryButton} from "../../Buttons/SecondaryButton";

export const SecondaryAction = ({label, route, method = "get"}) => {
    return (
        <SecondaryButton onClick={() => {
            Inertia[method](route)
        }}>
            {label}
        </SecondaryButton>
    )
}
