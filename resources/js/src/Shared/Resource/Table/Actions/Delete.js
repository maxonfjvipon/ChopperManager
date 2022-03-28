import React from "react";
import {Button, Popconfirm, Tooltip} from "antd";
import {DeleteOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";

export const Delete = ({sureDeleteTitle, confirmHandler}) => {
    return (<Tooltip placement="topRight" title={Lang.get('tooltips.delete')}>
        <Popconfirm
            title={sureDeleteTitle}
            onConfirm={confirmHandler}
            okText={Lang.get('tooltips.popconfirm.yes')}
            cancelText={Lang.get('tooltips.popconfirm.no')}
        >
            <Button icon={<DeleteOutlined/>}/>
        </Popconfirm>
    </Tooltip>)
}
