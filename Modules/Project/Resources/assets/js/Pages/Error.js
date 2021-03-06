import React from 'react'
import {Result} from "antd";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import ErrorLayout from "../Components/ErrorLayout";

const  ErrorPage = ({ status }) => {
    const title = {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[status]

    const description = {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[status]

    return (
        <Result
            status={status.toString()}
            title={title}
            subTitle={description}
            extra={<PrimaryButton onClick={e => {
                history.back()
            }}>
                Go back
            </PrimaryButton>}
        />
    )
}

ErrorPage.layout = page => <ErrorLayout children={page}/>

export default ErrorPage
