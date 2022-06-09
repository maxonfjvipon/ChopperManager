import {useState} from 'react'
import {message} from "antd";

export const useHttp = () => {
    const [loading, setLoading] = useState(false)

    const request = async (url, method = 'GET', body = null, headers = {}, auth = false) => {
        setLoading(true)
        let response, data
        console.log(body)
        try {
            response = await axios.request({
                url,
                method,
                data: body,
            })
            data = response.data
        } catch (e) {
            setLoading(false)
            throw new Error(e)
        }
        if (response.status === 419) {
            setLoading(false)
            message.info('Your session may be expired. Reload the page')
            throw new Error()
        }
        if (response.statusText !== "OK") {
            setLoading(false)
            for (let key in data) {
                message.error(data[key])
            }
            throw new Error()
        }
        for (let key in data) {
            if (message.hasOwnProperty(key)) {
                message[key](data[key])
            }
        }
        setLoading(false)
        return data
    }

    const getRequest = async (url, auth = false) => {
        return await request(url, 'GET', null, {}, auth)
    }

    const postRequest = async (url, body = null, auth = false) => {
        return await request(url, 'POST', body, {}, auth)
    }

    const putRequest = async (url, body = null, auth = false) => {
        return await request(url, 'PUT', body, {}, auth)
    }

    const deleteRequest = async (url, body = null, auth = false) => {
        return await request(url, 'DELETE', body, {}, auth)
    }

    return {loading, getRequest, putRequest, postRequest, deleteRequest, request}
}
