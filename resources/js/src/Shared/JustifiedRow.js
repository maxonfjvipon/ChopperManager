import {Row} from "antd";
import React from "react";

export const JustifiedRow = ({children, gutter = [0, 0], ...rest}) => {
    return <Row style={{minHeight: "80vh"}} justify="space-around" align="middle" gutter={gutter} {...rest}>
        {children}
    </Row>
}
