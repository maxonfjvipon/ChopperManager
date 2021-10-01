import React from "react";
import {PrimaryButton} from "../../Buttons/PrimaryButton";
import {Space} from "antd";
import {BoxFlex} from "../../Box/BoxFlex";
import {useStyles} from "../../../Hooks/styles.hook";
import {ResourceContainerCard} from "../ResourceContainerCard";

export const Container = ({
                              children,
                              title,
                              backTitle,
                              backHref,
                              actionButtons,
                          }) => {
    const {margin} = useStyles()
    return (
        <>
            <ResourceContainerCard
                title={title}
                backHref={backHref}
                backTitle={backTitle}
            >
                {children}
            </ResourceContainerCard>
            <BoxFlex style={margin.top(16)}>
                <Space>
                    {actionButtons}
                </Space>
            </BoxFlex>
        </>
    )
}