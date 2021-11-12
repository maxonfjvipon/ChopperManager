import React, {useEffect, useRef, useState} from 'react'
import {Dropdown, Menu} from "antd";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {DownOutlined, FileImageOutlined, FilePdfOutlined, UploadOutlined} from "@ant-design/icons";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {Inertia} from "@inertiajs/inertia";

export const PumpTechInfoUploader = () => {
    const [data, setData] = useState({files: null, folder: null})
    const imagesRef = useRef()
    const filesRef = useRef()
    const {tRoute} = useTransRoutes()

    useEffect(() => {
        if (data.files && data.folder) {
            Inertia.post(tRoute('pumps.import.media'), data, {
                forceFormData: true,
                preserveScroll: true,
                preserveState: true,
                only: [],
            })
            setData({files: null, folder: null})
        }
    }, [data])

    const MenuItem = ({folder, children, images = true, icon=<FileImageOutlined/>}) => {
        return <Menu.Item key={folder} icon={icon} onClick={() => {
            setData({
                ...data, folder: folder
            })
            if (images) {
                imagesRef.current.click()
            } else {
                filesRef.current.click()
            }
        }}>
            {children}
        </Menu.Item>
    }

    return <>
        <input id={"input-images"} hidden type="file" multiple onClick={(e) => {
            e.target.value = null
        }} ref={imagesRef} onChange={e => {
            setData({
                ...data,
                files: e.target.files
            })
        }} accept=".jpeg, .jpg, .png"/>
        <input id={"input-files"} hidden type="file" multiple onClick={(e) => {
            e.target.value = null
        }} ref={filesRef} onChange={e => {
            setData({
                ...data,
                files: e.target.files
            })
        }} accept=".pdf"/>
        <Dropdown key="upload-actions" trigger={['click']} arrow overlay={
            <Menu key="menu">
                <Menu.ItemGroup key="menu-item-group-1" title="Images (max 300, .jpg, .jpeg, .png)">
                    <MenuItem key="images" folder="pumps/images">
                        Pumps
                    </MenuItem>
                    <MenuItem key="images/sizes" folder="pumps/images/sizes">
                        Pump sizes
                    </MenuItem>
                    <MenuItem key="images/electric_diagrams" folder="pumps/images/electric_diagrams">
                        Pump electric diagrams
                    </MenuItem>
                    <MenuItem key="images/cross_sectional_drawings" folder="pumps/images/cross_sectional_drawings">
                        Pump cross sectional drawings
                    </MenuItem>
                </Menu.ItemGroup>
                <Menu.ItemGroup key="menu-item-group-2" title="Files (max 300, .pdf)">
                    <MenuItem key="files" folder="pumps/files" images={false} icon={<FilePdfOutlined/>}>
                        Files
                    </MenuItem>
                </Menu.ItemGroup>
            </Menu>
        }>
            <PrimaryButton>
                <UploadOutlined/>Upload tech-info<DownOutlined/>
            </PrimaryButton>
        </Dropdown>
    </>
}
