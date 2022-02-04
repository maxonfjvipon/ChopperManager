import React from "react";
import {Select} from "antd";

export const Selection = ({placeholder, defaultValue, options, ...rest}) => {
    return (
        <Select
            options={[...options.map(option => {
                return {
                    label: option.customValue || option.name || option.value,
                    value: option.id
                }
            })]}
            filterOption={(input, option) => {
                return option.label.toString().toLowerCase().includes(input.toLowerCase())
            }}
            placeholder={placeholder || ""}
            {...rest}
        />
    )
}
