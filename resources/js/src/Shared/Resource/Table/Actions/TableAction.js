import React from 'react'
import {Button, Tooltip} from "antd";

export const TableAction = ({title, clickHandler, icon}) => {
    return <Tooltip placement="topRight" title={title}>
        <Button
            onClick={clickHandler}
            icon={icon}
        />
    </Tooltip>
}
