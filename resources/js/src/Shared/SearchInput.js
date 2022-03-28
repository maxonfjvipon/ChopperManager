import React from "react";
import {Input, Space} from "antd";
import {PrimaryButton} from "./Buttons/PrimaryButton";

export const SearchInput = ({id, loading = false, searchClickHandler, placeholder}) => {
    return (
        <Space style={{marginBottom: "8px"}}>
            <Input
                onPressEnter={searchClickHandler}
                placeholder={placeholder}
                id={id}
                allowClear
                style={{width: 300}}
            />
            <PrimaryButton loading={loading} onClick={searchClickHandler}>
                Поиск
            </PrimaryButton>
        </Space>
    )
}
