import {Dropdown, Menu} from "antd";
import {DownOutlined, FilePdfOutlined, UploadOutlined} from "@ant-design/icons";
import {PrimaryButton} from "../../Buttons/PrimaryButton";
import React from "react";
import {Inertia} from "@inertiajs/inertia";

export const ComplexPrimaryAction = ({label, actions}) => {
    return (
        <Dropdown key="upload-actions" trigger={['click']} arrow overlay={
            <Menu key="menu">
                {actions.map(action => (
                    <Menu.Item key={action.label} icon={action.icon || null} onClick={() => {
                        Inertia[action.method || 'get'](action.route)
                    }}>
                        {action.label}
                    </Menu.Item>
                ))}
            </Menu>
        }>
            <PrimaryButton>
                {label}<DownOutlined/>
            </PrimaryButton>
        </Dropdown>
    )
}
