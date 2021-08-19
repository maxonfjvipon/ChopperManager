import React from "react";
import {Card, Typography} from "antd";
import Meta from "antd/es/card/Meta";
import {BoxFlexCenter} from "./Box/BoxFlexCenter";

export const DashboardCard = ({title, src, onClick, style}) => {
    return (
        <Card hoverable cover={<img alt="example" src={src}/>} onClick={onClick} style={{...style, borderRadius: 10}}>
            <Meta
                description={
                    <BoxFlexCenter>
                        <Typography color="primary">
                            {title}
                        </Typography>
                    </BoxFlexCenter>
                }
            />
        </Card>
    )
}
