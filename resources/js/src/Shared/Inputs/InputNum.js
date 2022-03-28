import React from 'react'
import {InputNumber} from "antd";

export const InputNum = ({children, ...rest}) => {
    return (
        <InputNumber
            {...rest}
            formatter={value => value.replace(',', '.')}
            parser={value => value.replace(',', '.')}
        >
            {children}
        </InputNumber>
    )
}
