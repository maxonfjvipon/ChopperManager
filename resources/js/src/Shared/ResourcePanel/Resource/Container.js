import React from "react";
import {PrimaryButton} from "../../Buttons/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";
import {Card, Divider, Space} from "antd";
import {BoxFlex} from "../../Box/BoxFlex";
import {Link} from "@inertiajs/inertia-react";
import {useStyles} from "../../../Hooks/styles.hook";
import {RoundedCard} from "../../RoundedCard";

export const Container = ({
                              form,
                              buttonLabel,
                              children,
                              title,
                              backTitle,
                              backHref,
                              extraButtons
                          }) => {
    const {margin} = useStyles()
    return (
        <>
            <RoundedCard
                title={title}
                extra={<Link href={backHref}>{"<<" + backTitle}</Link>}
            >
                <div className="site-layout-background">
                    {children}
                </div>
            </RoundedCard>
            <BoxFlex style={margin.top(16)}>
                <Space>
                    <PrimaryButton htmlType="submit" form={form}>
                        {buttonLabel}
                    </PrimaryButton>
                    {extraButtons}
                </Space>
            </BoxFlex>
        </>
    )
}
