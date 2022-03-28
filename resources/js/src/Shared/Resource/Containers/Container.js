import React from 'react'
import {BoxFlex} from "../../Box/BoxFlex";

export const Container = ({children, style}) => {
    return (
        <BoxFlex style={{flexDirection: "column", flex: "auto", ...style}}>
            {children}
        </BoxFlex>
    )
}
