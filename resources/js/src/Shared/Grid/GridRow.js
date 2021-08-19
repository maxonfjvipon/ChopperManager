import React from "react";
import {Row} from "antd";

export const GridRow = ({children, hor = 16, ver = 16, ...rest}) => {
    return (
        <Row gutter={[hor, ver]} {...rest}>
            {children}
        </Row>
    )
}
