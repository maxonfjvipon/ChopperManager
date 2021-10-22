import React from "react";
import {Button, Tooltip} from "antd";
import {EditOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const Edit = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.edit')}>
            <Button
                onClick={clickHandler}
                icon={<EditOutlined/>}
            />
        </Tooltip>
    )
}
