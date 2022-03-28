import React from "react";
import {Button, Tooltip} from "antd";
import {CopyOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const Clone = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.clone')}>
            <Button
                onClick={clickHandler}
                icon={<CopyOutlined/>}
            />
        </Tooltip>
    )
}
