import React from "react";
import {Button, Tooltip} from "antd";
import {PrinterOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const Export = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.export')}>
            <Button
                onClick={clickHandler}
                icon={<PrinterOutlined/>}
            />
        </Tooltip>
    )
}
