import React from 'react'
import {Space} from "antd";

export const ActionsContainer = ({actions, ...rest}) => {
    return (
        <Space size={[8, 0]} {...rest}>
            {actions}
        </Space>
    )
}
