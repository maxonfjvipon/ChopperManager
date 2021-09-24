import React from "react";
import {Button, Tooltip} from "antd";
import {EditOutlined} from "@ant-design/icons";

export const View = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title="Show">
            <Button
                onClick={clickHandler}
                icon={<EditOutlined/>}
            />
        </Tooltip>
    )
}
