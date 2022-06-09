import React from "react";
import {Button, Tooltip} from "antd";
import {EditOutlined} from "@ant-design/icons";
import Lang from "../../../../../translation/lang";
import {TableAction} from "./TableAction";

export const Edit = ({clickHandler}) => {
    return <TableAction clickHandler={clickHandler} title="Редактировать" icon={<EditOutlined/>}/>
}
