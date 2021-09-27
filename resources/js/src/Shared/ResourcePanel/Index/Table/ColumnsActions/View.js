import React from "react";
import {Button, Tooltip} from "antd";
import {EyeOutlined} from "@ant-design/icons";

export const View = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title="Show">
            <Button
                onClick={clickHandler}
                icon={<EyeOutlined/>}
            />
        </Tooltip>
    )
}
