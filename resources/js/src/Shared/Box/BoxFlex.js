import React from "react";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlex = ({children, style, ...rest}) => {
    const {displayFlex} = useStyles()

    return (
        <div style={{...displayFlex, ...style}} {...rest}>
            {children}
        </div>
    )
}
