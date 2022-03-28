import React from "react";
import {Button, Tooltip} from "antd";
import {EditOutlined} from "@ant-design/icons";

export const Edit = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title="Редактировать">
            <Button
                onClick={clickHandler}
                icon={<EditOutlined/>}
            />
        </Tooltip>
    )
}
