import {Link} from "@inertiajs/inertia-react";
import {RoundedCard} from "../Cards/RoundedCard";
import React from "react";

export const ResourceContainerCard = ({title, backHref, backTitle, children}) => {
    return (
        <RoundedCard
            title={title}
            extra={<Link href={backHref}>{"<<" + backTitle}</Link>}
        >
            <div className="site-layout-background">
                {children}
            </div>
        </RoundedCard>
    )
}
