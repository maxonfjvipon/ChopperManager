import {BoxFlex} from "./BoxFlex";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlexSpaceBetween = ({children, style, ...rest}) => {
    const {justifyContentSpaceBetween} = useStyles()

    return (
        <BoxFlex style={{...justifyContentSpaceBetween, ...style}} {...rest}>
            {children}
        </BoxFlex>
    )
}
