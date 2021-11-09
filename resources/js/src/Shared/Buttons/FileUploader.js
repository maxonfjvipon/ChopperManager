import {useForm} from "@inertiajs/inertia-react";
import {useEffect, useRef} from "react";
import React from "react";
import {Button} from "antd";
import {PrimaryButton} from "./PrimaryButton";
import {UploadOutlined} from "@ant-design/icons";

export const FileUploader = ({title, route}) => {
    const {data, setData, post, processing} = useForm({files: null})

    const ref = useRef()

    useEffect(() => {
        if (data.files) {
            post(route, data)
            setData({files: null})
        }
    }, [data])

    return (
        <>
            <input hidden type="file" multiple onClick={(e) => {
                e.target.value = null
            }} ref={ref} onChange={e => {
                // console.log(e)
                setData('files', e.target.files)
            }}/>
            <PrimaryButton
                style={{width: "100%"}}
                loading={processing}
                disabled={processing}
                onClick={(e) => {
                    e.preventDefault()
                    ref.current.click()
                }}><UploadOutlined/>{title}
            </PrimaryButton>
        </>
    )
}
