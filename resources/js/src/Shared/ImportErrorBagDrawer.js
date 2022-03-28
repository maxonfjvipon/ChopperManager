import React, {useEffect, useState} from 'react'
import {Col, Drawer, List, Typography} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import Row from "antd/es/descriptions/Row";
import {RoundedCard} from "./Cards/RoundedCard";

export const ImportErrorBagDrawer = ({title}) => {
    const {errorBag} = usePage().props.flash
    const [visible, setVisible] = useState(false)

    useEffect(() => {
        if (errorBag) {
            setVisible(true)
        }
        // console.log("error bag", errorBag)
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
            title={title}
        >
            {/*<Typography.Title level={3}>{title}</Typography.Title>*/}
            {errorBag && <List
                itemLayout="horizontal"
                dataSource={errorBag}
                renderItem={item => (
                    <RoundedCard style={{backgroundColor: "#FF604F", border: "3px solid #c44538", margin: "10px 0px"}}>
                        <List.Item>
                            <List.Item.Meta
                                title={<span style={{color: "white"}}>
                                    {item.head.key + ": " + item.head.value}
                                </span>}
                                description={<span style={{color: "white"}}>{item.message}</span>}
                            />
                        </List.Item>
                    </RoundedCard>
                )}
            />}
        </Drawer>
    )
}
