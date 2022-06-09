import React from 'react'
import {PrimaryButton} from "../../Buttons/PrimaryButton";

export const SubmitAction = ({label, form, ...rest}) => {
    return (
        <PrimaryButton form={form} htmlType="submit" {...rest}>
            {label}
        </PrimaryButton>
    )
}
