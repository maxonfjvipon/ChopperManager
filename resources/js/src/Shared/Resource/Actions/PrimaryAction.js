import React from 'react'
import {PrimaryButton} from "../../Buttons/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";

export const PrimaryAction = ({label, route, method = "get"}) => {
    return (
        <PrimaryButton
            onClick={() => {
                Inertia[method](route)
            }}
        >
            {label}
        </PrimaryButton>
    )
}
