import React from "react";
import {GridCol} from "./GridCol";

export const GridColWide = ({children, ...rest}) => {
    return (
        <GridCol xs={24} {...rest}>
            {children}
        </GridCol>
    )
}
