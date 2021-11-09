import React from 'react'
import {useStyles} from "../../../Hooks/styles.hook";
import {Typography} from "antd";
import {usePage} from "@inertiajs/inertia-react";

export const AppTitle = () => {
    const {margin, color} = useStyles()
    const {title} = usePage().props

    return (
        <div style={margin.top(0)}>
            <Typography.Title level={5} style={color("white")}>
                {title}
            </Typography.Title>
        </div>
    )
}
