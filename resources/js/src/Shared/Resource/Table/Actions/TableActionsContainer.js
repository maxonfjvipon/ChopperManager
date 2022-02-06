import React from "react";
import {ActionsContainer} from "../../Containers/ActionsContainer";
import {RoundedCard} from "../../../Cards/RoundedCard";
import {Button, Dropdown} from "antd";
import {MoreOutlined} from "@ant-design/icons";

export const TableActionsContainer = ({children}) => {
    if (!Array.isArray(children) || children.length < 5) {
        return <ActionsContainer actions={children} size="small"/>
    }
    return <Dropdown
        trigger={['click']}
        arrow
        placement="topRight"
        overlay={<RoundedCard style={{padding: 10}}>
            <ActionsContainer actions={children} size="small"/>
        </RoundedCard>}
    >
        <Button
            icon={<MoreOutlined/>}
        />
    </Dropdown>
}
