import React, {useEffect, useState} from "react";
import {PrimaryButton} from "../../Buttons/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";
import {Card, Input, Space} from "antd";
import {RoundedCard} from "../../Cards/RoundedCard";
import {BoxFlex} from "../../Box/BoxFlex";
import {Link} from "@inertiajs/inertia-react";

export const CContainer = ({
                               mainActionRoute,
                               mainActionButtonLabel,
                               mainActionComponent,
                               title,
                               children,
                               extraButtons,
                               extra,
                               type,
                           }) => {

    const backLink = (title, href) => (title && href)
        ? <Link href={href}>{"<<" + title}</Link>
        : (title && !href)
            ? <a href={"javascript:history.back()"}>{"<<s" + title}</a>
            : null

    const [noActions, setNoActions] = useState(true)

    useEffect(() => {
        if (mainActionComponent || (!mainActionComponent && mainActionButtonLabel && mainActionRoute)) {
            setNoActions(false)
        }
    }, [])

    return (
        <>
            <BoxFlex>
                <Space>
                    {(mainActionComponent && !noActions) && mainActionComponent}
                    {(!mainActionComponent && !noActions) &&
                    <PrimaryButton onClick={() => {
                        Inertia.get(mainActionRoute)
                    }}>
                        {mainActionButtonLabel}
                    </PrimaryButton>}
                    {extraButtons}
                </Space>
            </BoxFlex>
            <RoundedCard
                style={{
                    marginTop: noActions ? 0 : 16,
                    flex: "auto"
                }}
                title={title}
                extra={<Space>
                    {extra?.map(b => backLink(b.title, b.href))}
                </Space>}
                type={type}
            >
                <div className="site-layout-background">
                    {children}
                </div>
            </RoundedCard>
        </>
    )
}
