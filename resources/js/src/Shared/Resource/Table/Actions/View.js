import React from "react";
import {Button, Tooltip} from "antd";
import {EyeOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const View = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
            <Button
                onClick={clickHandler}
                icon={<EyeOutlined/>}
            />
        </Tooltip>
    )
}
