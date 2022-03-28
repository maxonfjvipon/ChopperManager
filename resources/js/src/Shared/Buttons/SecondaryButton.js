import React from "react";
import {Button} from "antd";

export const SecondaryButton = ({type='primary', children, ...rest}) => {
    return (
        <Button
            danger
            type={type}
            {...rest}
        >
            {children}
        </Button>
    )
}
