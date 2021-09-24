import React from "react";
import {Button, Tooltip} from "antd";
import {EditOutlined, CheckCircleFilled, CheckCircleOutlined} from "@ant-design/icons";

export const Toggle = ({clickHandler, enabled}) => {
    return (
        <Tooltip placement="topRight" title={enabled ? "Disable" : "Enable"}>
            <Button
                onClick={clickHandler}
                icon={enabled ? <CheckCircleFilled/> : <CheckCircleOutlined />}
            />
        </Tooltip>
    )
}
