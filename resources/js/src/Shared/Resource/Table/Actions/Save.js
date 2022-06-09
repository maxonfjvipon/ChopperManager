import React from "react";
import {SaveOutlined} from "@ant-design/icons";
import {TableAction} from "./TableAction";

export const Save = ({clickHandler}) => {
    return <TableAction clickHandler={clickHandler} icon={<SaveOutlined/>} title="Сохранить"/>
}
