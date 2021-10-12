import React, {useEffect, useState} from 'react'
import {Col, Drawer, List, Typography} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import Row from "antd/es/descriptions/Row";
import {RoundedCard} from "./RoundedCard";

export const ImportErrorBagDrawer = ({head, title}) => {
    const {errorBag} = usePage().props.flash
    const [visible, setVisible] = useState(false)

    useEffect(() => {
        if (errorBag) {
            setVisible(true)
        }
        console.log("error bag", errorBag)
    }, [errorBag])

    return (
        <Drawer
            width={500}
            placement="right"
            visible={visible}
            closable={false}
            onClose={() => {
                setVisible(false)
            }}
        >
            <Typography.Title level={3}>{title}</Typography.Title>
            <List
                itemLayout="horizontal"
                dataSource={errorBag}
                renderItem={item => (
                    <RoundedCard style={{backgroundColor: "#FF604F", border: "3px solid #c44538", margin: "10px 0px"}}>
                        <List.Item>
                            <List.Item.Meta
                                title={<span style={{color: "white"}}>{head.key + ": " + item[head.value]}</span>}
                                description={<span style={{color: "white"}}>{item.message}</span>}
                            />
                        </List.Item>
                    </RoundedCard>
                )}
            >

            </List>

            {/*/!*<Row gutter={[10, 10]}>*!/*/}
            {/*    {errorBag && errorBag.map(error => (*/}
            {/*        // <Col xs={24}>*/}
            {/*            <RoundedCard style={{backgroundColor: "red"}}>*/}
            {/*                <span>{error.message}</span>*/}
            {/*                /!*{items.map(item => (*!/*/}
            {/*                /!*    <span>{item.value}</span>*!/*/}
            {/*                /!*))}*!/*/}
            {/*                /!*{items !== undefined && items.map(item => (*!/*/}
            {/*                /!*    <span style={{color: 'white'}}>{item.key + ": " + error[item.value]}</span>*!/*/}
            {/*                /!*))}*!/*/}
            {/*            </RoundedCard>*/}
            {/*        // </Col>*/}
            {/*    ))}*/}
            {/*/!*</Row>*!/*/}
        </Drawer>
    )
}
