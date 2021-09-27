import React, {useEffect, useState} from "react";
import {PrimaryButton} from "../../Buttons/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";
import {Card, Input, Space} from "antd";
import {RoundedCard} from "../../RoundedCard";
import {BoxFlex} from "../../Box/BoxFlex";
import {Link} from "@inertiajs/inertia-react";

export const Container = ({
                              mainActionRoute,
                              mainActionButtonLabel,
                              mainActionComponent,
                              title,
                              children,
                              extraButtons,
                              backHref,
                              backTitle,
                              type,
                          }) => {
    const backLink = (backHref && backTitle)
        ? <Link href={backHref}>{"<<" + backTitle}</Link>
        : (backTitle && !backHref)
            ? <a href={"javascript:history.back()"}>{"<<" + backTitle}</a>
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
                extra={backLink}
                type={type}
            >
                <div className="site-layout-background">
                    {children}
                </div>
            </RoundedCard>
        </>
    )
}
