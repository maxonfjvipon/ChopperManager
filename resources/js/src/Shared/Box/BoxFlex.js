import React from "react";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlex = ({children, styles, ...rest}) => {
    const {displayFlex} = useStyles()

    return (
        <div style={{...displayFlex, ...styles}} {...rest}>
            {children}
        </div>
    )
}
