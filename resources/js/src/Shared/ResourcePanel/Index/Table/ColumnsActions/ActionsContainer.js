import React from "react";
import {Space} from "antd";

export const ActionsContainer = ({children}) => {
    return (
        <Space size="small">
            {children}
        </Space>
    )
}
