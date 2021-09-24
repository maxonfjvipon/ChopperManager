import React from "react";
import {Button, Popconfirm, Tooltip} from "antd";
import {DeleteOutlined} from "@ant-design/icons";

export const Delete = ({sureDeleteTitle, confirmHandler}) => {
    return (<Tooltip placement="topRight" title='Delete'>
        <Popconfirm
            title={sureDeleteTitle}
            onConfirm={confirmHandler}
            okText="Yes"
            cancelText="No"
        >
            <Button icon={<DeleteOutlined/>}/>
        </Popconfirm>
    </Tooltip>)
}
