import React from "react";
import {Col, Form, Row} from "antd";

export const ItemsForm = ({
                              form,
                              withRow = false,
                              layout = "horizontal",
                              items,
                              labelSpan = 5,
                              onFinish,
                              // wrapperSpan = 19,
                              ...rest
                          }) => {
    const formItem = item => (
        <Form.Item {...item.values} key={item.values.name}>
            {item.input}
        </Form.Item>
    )

    const formItems = () => items.map(item => {
        return (
            <>
                {withRow && <Col span={item.span || 3}>
                    {formItem(item)}
                </Col>}
                {!withRow && formItem(item)}
            </>
        )
    })


    return (
        <Form onFinish={onFinish} form={form} layout={layout} labelCol={labelSpan}{...rest}>
            {items.map(item => (
                <Form.Item {...item.values} key={item.values?.name}>
                    {item.input}
                </Form.Item>
            ))}
            {/*{withRow && <Row gutter={[10, 0]}>*/}
            {/*    {formItems()}*/}
            {/*</Row>}*/}
            {/*{!withRow && formItems()}*/}
        </Form>
    )
}
