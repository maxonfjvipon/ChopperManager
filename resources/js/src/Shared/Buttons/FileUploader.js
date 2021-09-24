import {useForm} from "@inertiajs/inertia-react";
import {useEffect, useRef, useState} from "react";
import {Button, message} from "antd";
import {UploadOutlined} from "@ant-design/icons";
import React from "react";
import {PrimaryButton} from "./PrimaryButton";

export const FileUploader = ({title, route}) => {
    const {data, setData, post, processing} = useForm({file: null})
    const ref = useRef()

    useEffect(() => {
        if (data.file) {
            post(route, data)
            setData({file: null})
        }
    }, [data])

    return (
        <>
            <input hidden type="file" ref={ref} onChange={e => {
                setData('file', e.target.files[0])
            }}/>
            <PrimaryButton loading={processing} disabled={processing} onClick={() => {
                ref.current.click()
            }}>
                <UploadOutlined/>{title}
            </PrimaryButton>
        </>
    )
}
