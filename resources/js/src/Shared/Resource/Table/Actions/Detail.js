import React from "react";
import {Button, Tooltip} from "antd";
import Lang from "../../../../../translation/lang";
import {PlusCircleOutlined} from "@ant-design/icons";

export const Detail = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.detail')}>
            <Button
                onClick={clickHandler}
                icon={<PlusCircleOutlined/>}
            />
        </Tooltip>
    )
}
