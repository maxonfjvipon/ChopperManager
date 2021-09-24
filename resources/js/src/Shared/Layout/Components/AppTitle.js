import React from 'react'
import {useStyles} from "../../../Hooks/styles.hook";
import {Typography} from "antd";

export const AppTitle = () => {
    const {margin, color} = useStyles()

    return (
        <div style={margin.top(0)}>
            <Typography.Title level={5} style={color("white")}>
                Pump Manager
            </Typography.Title>
        </div>
    )
}
