import React from "react";
import {Button} from "antd";

export const PrimaryButton = (props) => {
    return (
        <Button
            type='primary'
            {...props}
        >
            {props.children}
        </Button>
    )
}
