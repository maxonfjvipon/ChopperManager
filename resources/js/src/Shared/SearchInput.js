import React from "react";
import {Input, Space} from "antd";
import Lang from "../../translation/lang";
import {PrimaryButton} from "./Buttons/PrimaryButton";

export const SearchInput = ({id, loading = false, searchClickHandler, placeholder, width = 300, ...rest}) => {
    return (
        <Space style={{marginBottom: "8px"}}>
            <Input
                onPressEnter={searchClickHandler}
                placeholder={placeholder}
                id={id}
                allowClear
                style={{width: width}}
            />
            <PrimaryButton loading={loading} onClick={searchClickHandler} {...rest}>
                {Lang.get('pages.pumps.search.button')}
            </PrimaryButton>
        </Space>
    )
}
