import React from "react";
import {Button, Tooltip} from "antd";
import {PieChartOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const Statistics = ({clickHandler}) => {
    return (
        <Tooltip placement="topRight" title={Lang.get('tooltips.statistics')}>
            <Button
                onClick={clickHandler}
                icon={<PieChartOutlined/>}
            />
        </Tooltip>
    )
}
