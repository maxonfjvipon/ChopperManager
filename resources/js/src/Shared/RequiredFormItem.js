import {Form} from "antd";
import React from "react";
import {useInputRules} from "../Hooks/input-rules.hook";

export const RequiredFormItem = ({children, name, className, ...rest}) => {
    const {rules} = useInputRules()

    return (
        <Form.Item name={name} rules={[rules.required]} {...rest} className={className}>
            {children}
        </Form.Item>
    )
}
