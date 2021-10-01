import {useState} from 'react'
import {message} from "antd";


export const useHttp = () => {
    const [loading, setLoading] = useState(false)

    const request = async (url, method = 'GET', body = null, headers = {}, auth = false) => {
        setLoading(true)
        if (body) {
            body = JSON.stringify(body)
            headers['Content-type'] = 'application/json'
        }
        if (auth) {
            headers['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content
        }
        let response, data
        try {
            response = await fetch(url, {method, body, headers})
            data = await response.json()
        } catch (e) {
            console.log(e)
        }

        if (!response.ok) {
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
