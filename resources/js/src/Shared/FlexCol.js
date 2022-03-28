import {Col} from "antd";
import React from 'react'

export const FlexCol = ({children, style, ...rest}) => {
    return (
        <Col style={{...style, display: "flex"}} {...rest}>
            {children}
        </Col>
    )
}
