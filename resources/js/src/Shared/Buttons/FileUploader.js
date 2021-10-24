import {useForm} from "@inertiajs/inertia-react";
import {useEffect, useRef} from "react";
import {UploadOutlined} from "@ant-design/icons";
import React from "react";
import {PrimaryButton} from "./PrimaryButton";

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
                loading={processing}
                disabled={processing}
                onClick={() => {
                    ref.current.click()
                }}>
                <UploadOutlined/>{title}
            </PrimaryButton>
        </>
    )
}
