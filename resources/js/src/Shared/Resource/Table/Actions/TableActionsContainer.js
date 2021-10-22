import React from "react";
import {ActionsContainer} from "../../Containers/ActionsContainer";

export const TableActionsContainer = ({children}) => {
    return (
        <ActionsContainer actions={children} size="small"/>
    )
}
