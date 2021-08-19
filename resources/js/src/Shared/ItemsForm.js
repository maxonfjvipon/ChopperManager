import React from "react";
import {Form} from "antd";

export const ItemsForm = ({form, layout = "horizontal",items, labelSpan = 5, wrapperSpan = 19, ...rest}) => {
    return (
        <Form form={form} layout={layout} labelCol={{xs: labelSpan + 10, sm: labelSpan + 3, md: labelSpan}} {...rest}>
            {items.map(item => (
                <Form.Item {...item.values} key={item.values.name}>
                    {item.input}
                </Form.Item>
            ))}
        </Form>
    )
}
