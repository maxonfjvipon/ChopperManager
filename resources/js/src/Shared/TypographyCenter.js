import {useStyles} from "../Hooks/styles.hook";
import {Typography} from "antd";

export const TypographyCenter = ({children, ...rest}) => {
    const {textAlignCenter} = useStyles()

    return (
        <Typography style={textAlignCenter} {...rest}>
            {children}
        </Typography>
    )
}
