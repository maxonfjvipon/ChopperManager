import {Link, useForm} from "@inertiajs/inertia-react";
import {useEffect, useRef} from "react";
import React from "react";
import {Button} from "antd";

export const ImageUploader = ({title, route, folder}) => {
    const {data, setData, post, processing} = useForm({files: null, folder: null})

    const ref = useRef()

    useEffect(() => {
        if (data.files && data.folder) {
            post(route, data)
            setData({files: null, folder: null})
        }
    }, [data])

    return (
        <>
            <input hidden type="file" multiple onClick={(e) => {
                e.target.value = null
            }} ref={ref} onChange={e => {
                setData({files: e.target.files, folder: folder})
            }} accept=".jpg, .jpeg, .png"
            />
            <Link
                // style={{width: "100%"}}
                // type={"link"}
                // loading={processing}
                // disabled={processing}
                onClick={(e) => {
                    e.preventDefault()
                    ref.current.click()
                }}
            >
                {title}
            </Link>
        </>
    )
}
