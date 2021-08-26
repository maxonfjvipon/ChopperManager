import React from "react";
import {Col, Form, Row} from "antd";

export const ItemsForm = ({
                              form,
                              withRow = false,
                              layout = "horizontal",
                              items,
                              labelSpan = 5,
                              wrapperSpan = 19,
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
        <Form form={form} layout={layout}
              labelCol={{xs: labelSpan - 5, md: labelSpan - 3 ,xxl: labelSpan}}
              // labelCol={{xs: 10, sm: ,md:}}
              {...rest}>
            {withRow && <Row gutter={[10, 0]}>
                {formItems()}
            </Row>}
            {!withRow && formItems()}
        </Form>
    )
}
