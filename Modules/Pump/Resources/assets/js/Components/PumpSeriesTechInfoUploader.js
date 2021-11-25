import React, {useEffect, useRef, useState} from 'react'
import {Dropdown, Menu} from "antd";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {DownOutlined, FileImageOutlined, FilePdfOutlined, UploadOutlined} from "@ant-design/icons";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {Inertia} from "@inertiajs/inertia";
import Lang from '../../../../../../resources/js/translation/lang'


export const PumpSeriesTechInfoUploader = () => {
    const [data, setData] = useState({files: null, folder: null})
    const imagesRef = useRef()
    const filesRef = useRef()
    const {tRoute} = useTransRoutes()

    useEffect(() => {
        if (data.files && data.folder) {
            Inertia.post(tRoute('pump_series.import.media'), data, {
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
        {/*<input id={"input-files"} hidden type="file" multiple onClick={(e) => {*/}
        {/*    e.target.value = null*/}
        {/*}} ref={filesRef} onChange={e => {*/}
        {/*    setData({*/}
        {/*        ...data,*/}
        {/*        files: e.target.files*/}
        {/*    })*/}
        {/*}} accept=".pdf"/>*/}
        <Dropdown key="upload-actions" trigger={['click']} arrow overlay={
            <Menu key="menu">
                <Menu.ItemGroup key="menu-item-group-1" title={Lang.get('pages.pump_series.index.upload_images.images.title')}>
                    <MenuItem key="images" folder="pump_series/images">
                        {Lang.get('pages.pump_series.index.upload_images.images.icons')}
                    </MenuItem>
                </Menu.ItemGroup>
            </Menu>
        }>
            <PrimaryButton>
                <UploadOutlined/>{Lang.get('pages.pump_series.index.upload_images.title')}<DownOutlined/>
            </PrimaryButton>
        </Dropdown>
    </>
}
