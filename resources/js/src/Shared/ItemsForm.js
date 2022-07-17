import React from "react";
import {Col, Form, Row} from "antd";

export const ItemsForm = ({
                              form,
                              withRow = false,
                              layout = "horizontal",
                              items,
                              labelSpan = 5,
                              onFinish,
                              ...rest
                          }) => {
    return (
        <Form onFinish={onFinish} form={form} layout={layout} labelCol={labelSpan} {...rest}>
            {items.map(item => (
                <Form.Item {...item.values} key={item.values?.name}>
                    {item.input}
                </Form.Item>
            ))}
        </Form>
    )
}
