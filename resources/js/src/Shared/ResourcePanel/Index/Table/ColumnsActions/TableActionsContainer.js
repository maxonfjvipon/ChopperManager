import React from "react";
import {Space} from "antd";
import {ActionsContainer} from "../../../../Resource/Containers/ActionsContainer";

export const TableActionsContainer = ({children}) => {
    return (
        <ActionsContainer actions={children} size="small"/>
    )
}
