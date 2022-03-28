import React from 'react'
import {PrimaryButton} from "../../Buttons/PrimaryButton";

export const SubmitAction = ({label, form}) => {
    return (
        <PrimaryButton form={form} htmlType="submit">
            {label}
        </PrimaryButton>
    )
}
