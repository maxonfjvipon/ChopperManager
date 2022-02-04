import React from "react";
import {Button, Tooltip} from "antd";
import {SaveOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const Save = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.save')}>
            <Button
                onClick={clickHandler}
                icon={<SaveOutlined/>}
            />
        </Tooltip>
    )
}
