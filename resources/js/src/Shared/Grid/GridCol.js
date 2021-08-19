import React from "react";
import {Col} from "antd";

export const GridCol = ({children, ...rest}) => {
    return (
        <Col {...rest}>
            {children}
        </Col>
    )
}
